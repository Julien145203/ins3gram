# Utilisation du projet ins3gram

![image](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white
)
![image](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![image](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white
)
![image](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![image](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)
![image](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white) ![image](http://img.shields.io/badge/-PHPStorm-181717?style=for-the-badge&logo=phpstorm&logoColor=white)
![image](https://img.shields.io/badge/Codeigniter-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white)
![image](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=Composer&logoColor=white)

## Informations
Ceci est un projet fait sous [CodeIgniter 4](https://www.codeigniter.com/user_guide/index.html).

## Création de votre projet

1. Créer sur GitHub.com dans vos répertoires privés un repo pour votre projet.
2. Cloner ce repo sur votre ordinateur pour pouvoir travailler dessus.
3. Cloner ensuite ce projet. Passez par Github Desktop et les boutons présent en haut sur Github.com
4. Une fois le dossier cloné, ouvrez le et supprimer le dossier ```.git``` et ```.idea``` s'ils sont présents.
5. Ouvrez le dossier de votre projet et celui de ce projet, couper/coller tout le contenu de ce projet dans le votre.

### Initialiser le projet

Ouvrez votre projet avec phpstorm.

Ouvrez un terminal (`Alt + F12`).

Puis utiliser la commande suivante :
```
composer install
```
(vous pouvez utiliser composer update aussi)

```
docker-compose up -d
```

Puis

```
php spark migrate
```
Ensuite vous pouvez vérifier le bon fonctionnement à l'aide de la commande suivante :
```
php spark serve
```
Qui va ouvrir créer un serveur de développement local à l'adresse http://localhost:8080 (attention ce n'est pas
https ! )

Vous pouvez vous connecter avec le login et le mdp suivant : ```admin@admin.fr / admin```


Vous avez aussi un accés à un phpmyadmin  de développement local à l'adresse http://localhost:8081 (attention ce
n'est pas https ! ).

Si le phpmyadmin ne fonctionne pas il faut verifier que docker est bien lancé et vos container
aussi.

Pour vous connecter au phpmyadmin il faut utiliser ```root / root```