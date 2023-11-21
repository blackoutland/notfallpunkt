FROM alpine:3.15

ENV NFP_VERSION 0.0.1

RUN apk update && apk add bash hostapd iptables dnsmasq openssl && rm -rf /var/cache/apk/*
#RUN echo "" > /var/lib/dhcp/dhcpd.leases
ADD wifi.sh /bin/wifi.sh
RUN chmod 744 /bin/wifi.sh

ENTRYPOINT [ "/bin/wifi.sh" ]