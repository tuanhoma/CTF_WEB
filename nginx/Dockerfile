FROM nginx:latest

COPY conf.d /etc/nginx/conf.d

RUN mkdir -p /var/log/nginx

CMD ["nginx", "-g", "daemon off;"]