FROM golang:alpine AS builder

LABEL maintainer="TurboPress"
LABEL email="barry@turbopress.pro"

RUN apk add --no-cache git

RUN go install github.com/caddyserver/xcaddy/cmd/xcaddy@latest

RUN xcaddy build \
    --with github.com/caddyserver/replace-response

FROM caddy:alpine

LABEL maintainer="TurboPress"
LABEL email="barry@turbopress.pro"

RUN apk add --no-cache \
    nss-tools \
    bash \
    curl \
    openssl

COPY --from=builder /go/caddy /usr/bin/caddy

EXPOSE 80
EXPOSE 443
