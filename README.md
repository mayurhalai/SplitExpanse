# Splitexpense
Web application to manage sharing of bills.

## configuration
import expense.sql to your database.
set application/config/config.php according to your database settings.
Done!

## Run on docker
docker-compose up --build -d

set db password in docker/docker-compose.yml at (default password: admin)
    environment:
      MYSQL_ROOT_PASSWORD: admin

also make changes to application/config/config.php accordingly.

### Web admin password
    Default password will be 'collins', this can be changed on admin table by providing MD5 hash of password.
