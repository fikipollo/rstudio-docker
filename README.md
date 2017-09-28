RStudio server docker
===================

This docker image extends and distributes the following software:

#### rocker/rstudio docker
- [Docker image](https://hub.docker.com/r/rocker/rstudio-stable/).
- [GNU General Public License v2.0](https://raw.githubusercontent.com/rocker-org/rocker-versioned/master/LICENSE).

#### RStudio
- [Licensed under GNU AGPL version 3.](https://www.rstudio.com/).
- Citation
> RStudio Team (2015). RStudio: Integrated Development for R. RStudio, Inc., Boston, MA [Link](http://www.rstudio.com/).


# Build the image
The docker image for RStudio can be found in the [docker hub](https://hub.docker.com/r/fikipollo/rstudio/). However, you can rebuild is manually by running **docker build**.

```sh
sudo docker build -t rstudio .
```
Note that the current working directory must contain the Dockerfile file.

## Running the Container
The recommended way for running your RStudio docker is using the provided **docker-compose** script that resolves the dependencies and make easier to customize your instance. Alternatively you can run the docker manually.

## Quickstart

This procedure starts RStudio in a standard virtualised environment.

- Install [docker](https://docs.docker.com/engine/installation/) for your system if not previously done.
- `docker run -it -p 8098:80 fikipollo/rstudio`
- RStudio will be available at [http://localhost:8098/](http://localhost:8098/)

## Using the docker-compose file
Launching your RStudio docker is really easy using docker-compose. Just download the *docker-compose.yml* file and customize the content according to your needs. There are few settings that should be change in the file, follow the instructions in the file to configure your container.
To launch the container, type:
```sh
sudo docker-compose up
```
Using the *-d* flag you can launch the containers in background.

In case you do not have the Container stored locally, docker will download it for you.

# Install the image
You can run manually your containers using the following commands:

```sh
sudo docker run --name rstudio -v /your/data/location/rstudio-data:/home -e ADMIN_USER=youradminuser -e ADMIN_PASS=supersecret -p 8098:80 -d fikipollo/rstudio
```

In case you do not have the Container stored locally, docker will download it for you.

A short description of the parameters would be:
- `docker run` will run the container for you.

- `-p 8098:80` will make the port 80 (inside of the container) available on port 8098 on your host.
    Inside the container a RStudio server is running on port 8098 and that port can be bound to a local port on your host computer.

- `fikipollo/rstudio` is the Image name, which can be found in the [docker hub](https://hub.docker.com/r/fikipollo/rstudio/).

- `-d` will start the docker container in daemon mode.

- `-e VARIABLE_NAME=VALUE` changes the default value for a system variable.
The RStudio docker accepts the following variables that modify the behavior of the system in the docker.

    - **ADMIN_USER**, the name for the admin account. Using this account you can access to the admin portal (e.g. [http://yourserver:8098/admin.php](http://yourserver:8098/admin.php)) and manipulate the registered users in the system.
    - **ADMIN_PASS**, the password for the admin user.


# Version log
  - v0.9 September 2017: First version of the docker.
