#!/bin/bash
# VULN: wildcard expansion in tar — attacker can plant --checkpoint files
cd /backup
tar czf /backup/archive.tar.gz *
