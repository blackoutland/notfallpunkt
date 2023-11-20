#!/bin/bash

# Check if running in privileged mode
if [ ! -w "/sys" ] ; then
    echo "[Error] Not running in privileged mode."
    exit 1
fi

# Check environment variables
if [ ! "${INTERFACE}" ] ; then
    echo "[Error] An interface must be specified."
    exit 1
fi

# Default values
true ${SUBNET:=10.3.9.0}
true ${AP_ADDR:=10.3.9.1}
true ${PRI_DNS:=10.3.9.1}
true ${SSID:=notfallpunkt}
true ${CHANNEL:=11}
true ${HW_MODE:=g}

cat > "/etc/hostapd.conf" <<EOF
interface=${INTERFACE}
${DRIVER+"driver=${DRIVER}"}
ssid=${SSID}
hw_mode=${HW_MODE}
channel=${CHANNEL}
country_code=${COUNTRY_CODE}
wmm_enabled=1

# Activate channel selection for HT High Throughput (802.11an)
${HT_ENABLED+"ieee80211n=1"}
${HT_CAPAB+"ht_capab=${HT_CAPAB}"}

# Activate channel selection for VHT Very High Throughput (802.11ac)
${VHT_ENABLED+"ieee80211ac=1"}
${VHT_CAPAB+"vht_capab=${VHT_CAPAB}"}
EOF

# DNS Setup
cat > "/etc/dnsmasq.conf" <<EOF

interface=wlan0
no-dhcp-interface=eth0
dhcp-range=${DHCP_IP_START},${DHCP_IP_END},${DHCP_IP_NETMASK},${DHCP_IP_LEASE}
dhcp-option=option:dns-server,${AP_ADDR}

# Captive Portal
address=/#/${AP_ADDR}
EOF

# Setup interface and restart DHCP service
ip link set ${INTERFACE} up
ip addr flush dev ${INTERFACE}
ip addr add ${AP_ADDR}/24 dev ${INTERFACE}

# NAT settings
#echo "NAT settings ip_dynaddr, ip_forward"

#for i in ip_dynaddr ip_forward ; do
#  if [ $(cat /proc/sys/net/ipv4/$i) -eq 1 ] ; then
#    echo $i already 1
#  else
#    echo "1" > /proc/sys/net/ipv4/$i
#  fi
#done

#cat /proc/sys/net/ipv4/ip_dynaddr
#cat /proc/sys/net/ipv4/ip_forward

# Forward all HTTP and HTTPS requests
echo "Setting up forwarding rules to localhost for HTTP and HTTPS"
sudo iptables -t nat -A PREROUTING -i ${INTERFACE} -p tcp --dport 80 -j REDIRECT --to-ports 80
sudo iptables -t nat -A PREROUTING -i ${INTERFACE} -p tcp --dport 443 -j REDIRECT --to-ports 443

# HTTPS CERTIFICATE
CERT_CRT=/certs/cert.pem
CERT_KEY=/certs/key.pem
if [ ! -f $CERT_CRT ]; then
   echo "HTTPS certificate does not yet exist, creating..."
   exec openssl req -x509 -newkey rsa:4096 -keyout /certs/key.pem -out /certs/cert.pem -sha256 -days 3650 -nodes -subj "/C=DE/ST=NRW/L=BlackoutCity/O=Notfallpunkt/OU=IT/CN=10.3.9.1"
else
    echo "Using existing HTTPS certificate in $CERT_CRT"
fi

# DHCP
echo "Seeting up DHCP..."
if [ ! -d "/etc/dhcp" ] ; then
    mkdir /etc/dhcp
fi

cat > "/etc/dhcp/dhcpd.conf" <<EOF
option domain-name-servers ${PRI_DNS};
option subnet-mask ${SUBNET_MASK};
option routers ${AP_ADDR};
subnet ${SUBNET} netmask ${SUBNET_MASK} {
  range ${SUBNET::-1}100 ${SUBNET::-1}200;
}
EOF

echo "Starting DHCP server .."
dhcpd ${INTERFACE}

# Capture external docker signals
trap 'true' SIGINT
trap 'true' SIGTERM
trap 'true' SIGHUP

echo "Starting HostAP daemon ..."
/usr/sbin/hostapd /etc/hostapd.conf &

wait $!

