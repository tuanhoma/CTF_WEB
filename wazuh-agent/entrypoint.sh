#!/bin/bash

# Cập nhật địa chỉ Wazuh Manager
if [ -n "$WAZUH_MANAGER" ]; then
    sed -i "s/<address>.*<\/address>/<address>$WAZUH_MANAGER<\/address>/g" /var/ossec/etc/ossec.conf
fi

# Đảm bảo file tồn tại ở data volume và symlink sang etc
touch /var/ossec/data/client.keys
ln -sf /var/ossec/data/client.keys /var/ossec/etc/client.keys

# Tự động Enroll nếu chưa có key
if [ ! -s /var/ossec/etc/client.keys ]; then
  /var/ossec/bin/agent-auth -m $WAZUH_MANAGER -A ctf_web_agent
fi



# Khởi động agent
/var/ossec/bin/wazuh-control start

# Giữ cho container luôn chạy và xuất log ra màn hình
tail -f /var/ossec/logs/ossec.log
