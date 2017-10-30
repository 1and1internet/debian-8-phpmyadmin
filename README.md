# phpMyAdmin on Debian 8 (Jessie) Docker Image

## Description

This image provides an instance of [phpMyAdmin](https://www.phpmyadmin.net/) running on [Debian 8](https://www.debian.org/). This is created specifically to be run under [OpenShift Origin](https://www.openshift.org/) and [Kubernetes](https://kubernetes.io/), as well as any other standard Docker environment.

**Ensure you specify a user id (UID) other than zero. Running as root is not a supported configuration.**

## Current Status: Work In Progress

This image is currently an experimental work in progress.

## Environment Variables

 * ``PMA_ARBITRARY``
 * ``PMA_HOST``
 * ``PMA_VERBOSE``
 * ``PMA_PORT``
 * ``PMA_HOSTS``
 * ``PMA_PORTS``
 * ``PMA_CONTROL_HOST``
 * ``PMA_CONTROL_PORT``
 * ``PMA_CONTROL_USER``
 * ``PMA_CONTROL_PASSWORD``
 * ``PMA_ABSOLUTE_URI``
 * ``PMA_USER``
 * ``PMA_PASSWORD``
