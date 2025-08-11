FROM nginx:alpine

#ENV USERNAME=nginx
#ENV GROUPNAME=www-data
#
RUN apk add shadow && \
    apk add bash
#
#RUN usermod -aG ${GROUPNAME} ${USERNAME}
#
#RUN  touch /var/run/nginx.pid && \
#     chown -R nginx:www-data /var/cache/nginx /var/run/nginx.pid

# нужно запускать от рута, чтобы запустить php-fpm и supervisor, но нужно запускать не от рута, чтобы безопасность)
#USER nginx:www-data

CMD ["nginx","-g","daemon off;"]
