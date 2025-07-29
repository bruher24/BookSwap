FROM nginx:alpine

ENV USER=nginx
ENV GROUPNAME=www-data
ENV UID=101

RUN apk add shadow && \
    usermod -aG www-data nginx

USER nginx

CMD ["nginx","-g","daemon off;"]
