# Wordpress SSL docker environment

## Install
1. clone the repository
```
git clone https://github.com/benjaminchocron/smart.git {my_project_folder_name}
```
2. Get ssl certifications file with **your custom local domain {eg: my_domain.local}** and rename them  
domain.dev.pem  
domain.dev-key.pem  
3. Copy **your certs (domain.dev.pem & domain.dev-key.pem)** to ./server/conf/certs
4. Rename **.env-example** to **.env** and fill **.env** file
```
mv .env-example .env
nano .env
```
5. add **{my_domain.local}** to your hosts file
```
127.0.0.1   my_domain.local
```
6. Run in terminal:
```
docker-compose build --no-cache
docker-compose up -d
```
7. **Copy your personal theme and plugins** into wp-content/...  
> Download and install botiga theme from [here](https://athemes.com/theme/botiga/)  
8. **Update .gitignore file** with your plugin and themes
> remove  
    /wp-content/plugins/  
    /wp-content/themes/  
> add  
    /wp-content/plugins/*  
    !wp-content/plugins/{my plugin}/  
    /wp-content/themes/*  
    !/wp-content/themes/{my theme}/
9. Browse to phpMyAdmin: [http://localhost:9000]  
10. Browse to your website [https://{my_domain.local}]   
11. Thats it!

[https://{my_domain.local}]: https://my_domain.local
[http://localhost:9000]: http://localhost:9000
