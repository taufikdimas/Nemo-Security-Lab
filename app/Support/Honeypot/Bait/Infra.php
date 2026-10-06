<?php

namespace App\Support\Honeypot\Bait;

/**
 * Tier 2 lures: source control and deployment artefacts. A fuzzer that has
 * already guessed one secret filename tends to walk the same neighbourhood, so
 * these sit where a real leaked repo would.
 *
 * The hosts and tokens are fabricated. 10.20.0.0/24 is used throughout because it
 * reads as an internal range without pointing at anything reachable from here.
 */
final class Infra
{
    public static function all(): array
    {
        return [
            '.git/config' => self::gitConfig(),
            '.docker/config.json' => self::dockerConfig(),
            'docker-compose.yml' => self::dockerCompose(),
            '.gitlab-ci.yml' => self::gitlabCi(),
            'k8s/deployment.yaml' => self::kubernetes(),
            '.dockerignore' => ['status' => 200, 'type' => 'text/plain', 'body' => ".git\n.env\nstorage/logs\nnode_modules\nvendor\n"],
            'composer.lock' => self::composerLock(),
        ];
    }

    private static function gitConfig(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[remote "origin"]
	url = git@git.garuda-siber.local:infra/secureops-platform.git
	fetch = +refs/heads/*:refs/remotes/origin/*
[branch "main"]
	remote = origin
	merge = refs/heads/main
[branch "release/2024.09"]
	remote = origin
	merge = refs/heads/release/2024.09
[user]
	name = Rina Hartono
	email = ops.admin@garuda-siber.local
TXT,
        ];
    }

    private static function dockerConfig(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                'auths' => [
                    'registry.garuda-siber.local' => [
                        'auth' => 'c2VjdXJvcHNfZGVwbG95OmRlcGxveU9uV2F5Rm9yQmFpdDAwMDA=',
                    ],
                ],
                'credsStore' => null,
                'currentContext' => 'default',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }

    private static function dockerCompose(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
version: "3.9"

services:
  app:
    build: .
    environment:
      - APP_ENV=production
      - APP_KEY=base64:VEhJUy1JUy1OT1QtQS1SRUFMLUtFWS1CQUlULU9OTFktRE8tTk9ULVVTRS0wMDAw
      - DB_HOST=10.20.0.14
      - DB_PASSWORD=St4g1ng!SecureOps2024
    ports:
      - "8000:8000"
    depends_on:
      - db
      - bridge

  db:
    image: mysql:8.0
    environment:
      - MYSQL_ROOT_PASSWORD=R00t!SecureOps2024
      - MYSQL_DATABASE=secureops_prod
      - MYSQL_USER=secureops_svc
      - MYSQL_PASSWORD=St4g1ng!SecureOps2024
    volumes:
      - db-data:/var/lib/mysql

  bridge:
    build: ./services/inventory-bridge
    environment:
      - BRIDGE_TOKEN=4f9c1a77b2e840d6a3c95e17b0d4826f
      - BRIDGE_UPSTREAM=http://10.20.0.31:9001
    ports:
      - "9001:9001"

volumes:
  db-data:
TXT,
        ];
    }

    private static function gitlabCi(): array
    {
        return [
            'status' => 200,
            'type' => 'text/plain',
            'body' => <<<'TXT'
stages:
  - build
  - test
  - deploy

variables:
  APP_ENV: production
  SECUREOPS_DEBUG_KEY: "staging-master-2023"

build:app:
  stage: build
  script:
    - docker build -t registry.garuda-siber.local/secureops:$CI_COMMIT_SHA .
    - docker login -u $REGISTRY_USER -p $REGISTRY_TOKEN registry.garuda-siber.local
    - docker push registry.garuda-siber.local/secureops:$CI_COMMIT_SHA

deploy:prod:
  stage: deploy
  when: manual
  script:
    - ssh deploy@10.20.0.9 "cd /srv/secureops && ./bin/release.sh $CI_COMMIT_SHA"
TXT,
        ];
    }

    private static function kubernetes(): array
    {
        return [
            'status' => 200,
            'type' => 'application/yaml',
            'body' => <<<'TXT'
apiVersion: apps/v1
kind: Deployment
metadata:
  name: secureops-app
  namespace: soc-production
spec:
  replicas: 3
  selector:
    matchLabels:
      app: secureops
  template:
    metadata:
      labels:
        app: secureops
    spec:
      containers:
        - name: app
          image: registry.garuda-siber.local/secureops:2024.09
          ports:
            - containerPort: 8000
          env:
            - name: DB_HOST
              value: "10.20.0.14"
            - name: DB_PASSWORD
              value: "St4g1ng!SecureOps2024"
            - name: INVENTORY_API_TOKEN
              value: "4f9c1a77b2e840d6a3c95e17b0d4826f"
          envFrom:
            - secretRef:
                name: secureops-secrets
          readinessProbe:
            httpGet:
              path: /up
              port: 8000
TXT,
        ];
    }

    private static function composerLock(): array
    {
        return [
            'status' => 200,
            'type' => 'application/json',
            'body' => json_encode([
                '_readme' => [
                    'This file locks the dependencies of your project to a known state',
                    'fabricated lure - not a real lock file',
                ],
                'content-hash' => '0000000000000000000000000000000f',
                'packages' => [
                    ['name' => 'laravel/framework', 'version' => 'v11.9.0'],
                    ['name' => 'guzzlehttp/guzzle', 'version' => '7.8.1'],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ];
    }
}