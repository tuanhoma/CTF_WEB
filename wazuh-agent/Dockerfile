FROM ubuntu:22.04

# Cài đặt các gói cần thiết
RUN apt-get update && apt-get install -y curl apt-transport-https lsb-release gnupg procps sudo

# Thêm Wazuh repository
RUN curl -s https://packages.wazuh.com/key/GPG-KEY-WAZUH | gpg --no-default-keyring --keyring gnupg-ring:/usr/share/keyrings/wazuh.gpg --import && chmod 644 /usr/share/keyrings/wazuh.gpg
RUN echo "deb [signed-by=/usr/share/keyrings/wazuh.gpg] https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list

# Cài đặt Wazuh agent đúng phiên bản 4.9.0
RUN apt-get update && apt-get install -y wazuh-agent=4.9.0-1

# Copy file khởi động và cấu hình log collector
COPY entrypoint.sh /entrypoint.sh
COPY ossec_extra.conf /ossec_extra.conf
RUN chmod +x /entrypoint.sh

# Append cấu hình log collector vào ossec.conf (chạy sau khi package đã cài)
RUN cat /ossec_extra.conf >> /var/ossec/etc/ossec.conf

ENTRYPOINT ["/entrypoint.sh"]
