# AWS Project Commands

## Project: Cloud-Based File Upload and Hosting System Using AWS

### Technologies
- AWS EC2
- Amazon S3
- Amazon CloudFront
- Amazon RDS
- AWS IAM
- Linux
- Nginx
- PHP

### 1. Linux Commands

```bash
pwd
ls -la
df -h
free -h
```

### 2. Nginx Commands

```bash
sudo systemctl start nginx
sudo systemctl enable nginx
sudo systemctl status nginx
sudo nginx -t
```

### 3. AWS CLI Commands

```bash
aws --version
aws sts get-caller-identity
aws s3 ls
```

### 4. Upload a File to Amazon S3

```bash
aws s3 cp sample.txt s3://YOUR_BUCKET_NAME/
```

### 5. List Files in Amazon S3

```bash
aws s3 ls s3://YOUR_BUCKET_NAME/
```

### 6. CloudFront Commands

```bash
aws cloudfront list-distributions
```

### 7. Troubleshooting

```bash
sudo tail -n 100 /var/log/nginx/error.log
curl -I http://localhost
sudo ss -tulpn
```

### Security Best Practices
- Use an EC2 IAM role for authorized S3 access.
- Never upload AWS credentials or private keys to GitHub.
- Apply least-privilege IAM permissions.
- Secure the database and enable HTTPS where appropriate.

**Author:** Bhushan Jagtap
