FROM alpine:3.15

ENV NFP_VERSION 0.0.1

# Hostapd, iptables, dhcp, bind, openssl -> networking
# libintl, gettext -> envsubst command
RUN apk update && \
    apk add bash hostapd iptables dhcp bind openssl libintl &&  \
    apk add --virtual build_deps gettext &&  \
    cp /usr/bin/envsubst /usr/local/bin/envsubst && \
    apk del build_deps && \
    rm -rf /var/cache/apk/*

RUN echo "" > /var/lib/dhcp/dhcpd.leases

ADD wifi.sh /bin/wifi.sh
RUN chmod 744 /bin/wifi.sh

ENTRYPOINT [ "/bin/wifi.sh" ]