<br />

<div align="center">
  <a href="https://github.com/O-Clock-Vanadium/projet-11-soundtrackify">
    <img src="public/img/back/logo2.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">SoundTrackify</h3>

  <h4 align="center">
    Rediscover your favorite game's soundtrack
    <br />
    <br />

    
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>
    <br />


<!-- ABOUT THE PROJECT -->
## About The Project

Today there is a lot of music services and platforms that allow you to have an access to your favorite artist's music. These services give you the opportunity to find these musics and to store them into customized playlists so you can listen to them in the order you choose to and create a new experience.

However, we noticed that it is hard for gamers and OST lovers to find these kinds of collections on major streaming services. As well, the websites dedicated to videogames music does not give you the opportunity to create your own playlist and does not have a friendly design easy to use.

Here are our mains goals towards this project :
* Offering direct access to videogame's albums with all their musics in order directly, without looking for it on the internet.
* Allowing to create a new user and listener experience by mixing many musics from differents albums and games into customized playlists.
* Giving users the opportunity to reconnect with gaming memories and discover new albums and new games as well.

This project takes place in a 6 month course we followed at the O'Clock school.
  
<p align="right">(<a href="#readme-top">back to top</a>)</p>
<br />

<!-- GETTING STARTED -->
## Getting Started

To get a local copy up follow these steps.

### Prerequisites

_Here are the ressources you need to use the project and how to install them. Follow the next steps if you do not have them_
* Composer
  ```sh
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  ```
  

### Installation

_Here are the instructions you have to follow in order to set up the app._


1. Clone the repo
   ```sh
   git clone git@github.com:O-Clock-Vanadium/projet-11-soundtrackify.git
   ```
2. Install packages and set up your DDB
   ```sh
   cd projet-11-soundtrackify
   composer install
   ```

3. Create .env.local file at project root and set up your DDB
    ```
   DATABASE_URL=mysql://YOUR_USER:YOUR_PASSWORD@127.0.0.1:3306/soundtrackify
   bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate
   ```

4. Import SQL file on your DDB ('/sources/bdd/soundtrackify.sql')


<p align="right">(<a href="#readme-top">back to top</a>)</p>
<br />

<!-- CONTACT -->
## Contact

* Emilie Derepierre - [GitHub profile](https://github.com/EmilieDe74) - [derepierreemilie@gmail.com](mailto:derepierreemilie@gmail.com)
* Loic Legard - [GitHub profile](https://github.com/LegardLoic) - [legardloic@gmail.com](mailto:legardloic@gmail.com)
* Gabriel Boukouch - [GitHub profile](https://github.com/Gabriel-Boukouch) - [gabriel.boukouch@gmail.com](mailto:gabriel.boukouch@gmail.com)

Project Link: [https://github.com/O-Clock-Vanadium/projet-11-soundtrackify](https://github.com/O-Clock-Vanadium/projet-11-soundtrackify)

<p align="right">(<a href="#readme-top">back to top</a>)</p>
<br />



<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

This space is dedicated to some ressources we used in the making of the project and that we found helpful.

* [Wonderful README template](https://github.com/othneildrew/Best-README-Template/tree/master)
* [Khinsider : videogames OST collection](https://downloads.khinsider.com/)
* [Tree structure making website](http://gloomaps.com)
* [How to slug properly under Symfony 5.4](https://symfony.com/doc/5.4/components/string.html#slugger)
* [Useful paginator bundle](https://github.com/KnpLabs/KnpPaginatorBundle)
* [Set up your cookie consent](https://github.com/Harborn-digital/cookie-consent-bundle)
* [Host your img online](http://imgur.com)
* [Last but not least, the best 6-month web development course](https://oclock.io/)

<p align="right">(<a href="#readme-top">back to top</a>)</p>
<br />
