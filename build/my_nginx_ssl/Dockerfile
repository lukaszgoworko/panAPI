FROM nginx:1.13.0-alpine

RUN apk update && apk add openssl

RUN mkdir -p /etc/nginx/certs/

RUN openssl dhparam -out /etc/nginx/certs/dh.pem 512

RUN echo ">> generating self signed cert" \
  && openssl req -x509 -newkey rsa:4086 \
  -subj "/C=XX/ST=XXXX/L=XXXX/O=XXXX/CN=api.pan.local" \
  -keyout "/etc/nginx/certs/key.pem" \
  -out "/etc/nginx/certs/cert.pem" \
  -days 3650 -nodes -sha256
