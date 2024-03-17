# <img src="https://i.imgur.com/xJtNxcK.png" width="40"> Alpha 3

## Description

This is the third alpha project for my school.
It is a website that allows the user to delete, view and upload data onto a premade database based on Cadastre of Real
Estate.
The processing is done by a backend written
in <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/2560px-PHP-logo.svg.png" width="40">.
The website is written
in <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/HTML5_Badge.svg/800px-HTML5_Badge.svg.png" width="20">,
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/CSS3_logo.svg/1024px-CSS3_logo.svg.png" width="20">
and <img src="https://iconape.com/wp-content/png_logo_vector/javascript-logo.png" width="18">.
<br>
This project was made by [Matyáš Závora](https://www.linkedin.com/in/matyas-zavora/) (me)
<br><a href="https://www.linkedin.com/in/matyas-zavora/"><img src="https://avatars.githubusercontent.com/u/105340917?v=4" width=10%></a>

#### Grade(s):

|           Percent score            |   53%    |
|:----------------------------------:|:--------:|
|            Czech grade             |  __3__   |
|  Configurability and universality  | __83%__  |
|  Architecture and design patterns  | __34%__  |
|   Usability and program control    | __90%__  |
|     Correctness and efficiency     | __48%__  |
|     Testing and error handling     | __46%__  |
| Documentation and code readability | __43%__ |

### Prerequisites

- [<u><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Xampp_logo.svg/2560px-Xampp_logo.svg.png" width="80"></u>](https://www.apachefriends.org/index.html) (
  or any other web server)
- A [<u><img src="https://upload.wikimedia.org/wikipedia/labs/8/8e/Mysql_logo.png" width="80"></u>](https://www.mysql.com) database
    - Note: It is already present in [<u><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Xampp_logo.svg/2560px-Xampp_logo.svg.png" width="80"></u>](https://www.apachefriends.org/index.html)
- [<u><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/2560px-PHP-logo.svg.png" width="40"></u>](https://www.php.net/downloads.php)
  7.4 (or higher)
- A web browser
- (Optional) Access to the internet (
  for <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Bootstrap_logo.svg/2560px-Bootstrap_logo.svg.png" width="24">)
    - Note: website is fully functional
      without <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Bootstrap_logo.svg/2560px-Bootstrap_logo.svg.png" width="24">,
      but it won't look as good as it is supposed to.

## Installation

#### Windows

1. Open <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Xampp_logo.svg/2560px-Xampp_logo.svg.png" width="80"> or any other web server
2. Clone this repository into the `htdocs` folder of <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Xampp_logo.svg/2560px-Xampp_logo.svg.png" width="80">
3. Start <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Xampp_logo.svg/2560px-Xampp_logo.svg.png" width="80"/> (Apache should be enough) (default port is 80)
4. Open `localhost[:port]/alpha3` in your browser
5. Import the database from `alpha3/database.sql` into your MySQL database
    1. Open `localhost[:port]/phpmyadmin`
    2. Click on `New` in the left sidebar
    3. Name the database `alpha3`
    4. Click on the `alpha3` database in the left sidebar
    5. Click on `Import` in the top bar
    6. Click on `Choose file` and select `alpha3/alpha3.sql`
    7. Click on `Go`
6. Configure the `config.php` file in the `alpha3` folder
    1. Open `alpha3/config.php` in a text editor
    2. Change the `db_server`, `db_user`, `db_pass`, `db_name` and `dp_port` to match your MySQL database

## Database diagram
<img src="./img/doc/db_diagram.png">
