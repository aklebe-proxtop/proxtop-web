# proxtop-web

**ProxTop** is a web desktop for Proxmox servers. Members of your homelab or team log in (optionally with LDAP) and see
the virtual machines they are allowed to use as cards on a desktop: with live status, start and stop, and a grace
period before a shutdown that can still be cancelled. Administrators define VM groups, permissions and time windows,
and manage the WireGuard VPN access of every member on a pfSense firewall.

This repository is the **public project template** of ProxTop for the
[Mercy Scaffold](https://github.com/aklebe-laravel/mercy-scaffold): the config and example business imports. The
application itself comes from the scaffold and these modules:

| Part                                                                     | Role                                                                    |
|--------------------------------------------------------------------------|-------------------------------------------------------------------------|
| [central-ui-module](https://github.com/aklebe-laravel/central-ui-module) | Proxmox desktop, VM jobs and schedules, WireGuard management            |
| [central-ui-theme](https://github.com/aklebe-laravel/central-ui-theme)   | admin theme of the desktop (child of mercy-blank)                       |
| Mercy base modules                                                       | users, ACL, LDAP, forms, data tables, website (see the scaffold README) |

## Features

- **VM desktop:** one card per VM with status; start and stop as queued jobs; switching off starts a countdown during
  which the shutdown can be cancelled
- **Permissions and schedules:** VM groups with access per user group (ACL) and time windows for start and stop
- **Proxmox data** is refreshed in the background, long API calls never delay a start or stop
- **WireGuard on pfSense:** create, renew and remove VPN peers of a member in one or several networks, download the
  client configs as a zip; also as artisan commands (`wg:member:set`, `wg:member:list`, `wg:member:remove`, `wg:doctor`)
- **LDAP login** and user and group sync (Acl module)

## Requirements

- the [requirements of the Mercy Scaffold](https://github.com/aklebe-laravel/mercy-scaffold#requirements)
- a Proxmox server with an API token for ProxTop
- optional: pfSense with the REST API package (`pfSense-pkg-RESTAPI`) for WireGuard, an LDAP server, Nginx Proxy Manager

## Quick start (dev)

```bash
git clone https://github.com/aklebe-laravel/mercy-scaffold.git proxtop && cd proxtop
git checkout "$(git describe --tags --abbrev=0)"            # latest scaffold release
git clone https://github.com/aklebe-proxtop/proxtop-web.git project
cp project/config/secrets.example.yaml project/config/secrets.yaml
# replace the example values in project/config/ and project/business_imports/ (see below)
composer install
./mercy install
```

Then load the data:

```bash
sail artisan ldap:sync-all          # only with LDAP: users and groups first
sail artisan deploy-env:terraform-modules --module_name=local:business-imports --force
```

Your copy will contain your hosts and network, so make it your own **private** repository:

```bash
git -C project remote set-url origin <your-private-repository>
git -C project push -u origin master
```

## What to change

The config files contain only what differs from the Mercy defaults; `./mercy config --all` shows every key and where
its value comes from. A new Mercy version brings new settings without changing these files.

| File                                                                            | Content                                                      |
|---------------------------------------------------------------------------------|--------------------------------------------------------------|
| `config/setup.yaml`                                                             | the product: name, image, theme, modules, pfSense URL        |
| `config/networks/example.yaml`                                                  | your network: own git server, DNS, Nginx Proxy Manager, LDAP |
| `config/dev.yaml`, `config/build.yaml`, `config/prod.yaml`                      | per stage: domain, release number                            |
| `config/secrets.yaml` (from `secrets.example.yaml`, not versioned)              | pfSense API key, Proxmox token, NPM and LDAP passwords       |
| `business_imports/module-deploy-env/0002/models/proxtop_servers.php`            | your Proxmox server(s) and VMs                               |
| `business_imports/module-deploy-env/0002/models/proxtop_property_templates.php` | VM groups, permissions and schedules                         |
| `business_imports/module-deploy-env/0002/models/users.php`                      | groups and desktop items of your users                       |
| `business_imports/module-deploy-env/0003/models/proxtop_wireguard_networks.php` | WireGuard networks (pfSense tunnels)                         |

Replace every example value (`example.com`, `192.0.2.x`, `my-org`). Run the business imports again after every change
of your data (command above).

Secrets of the imports are env vars like all other secrets: the Proxmox token is `env: PROXMOX_TOKEN_SECRET` in
`secrets.yaml`, the module config maps it, the import reads it with `config(...)`. Without it the import stops with an
error.

## Instances

One installation (a folder on a machine) is one instance: a few lines in `config/instances/<name>.yaml`, everything else
is inherited. Structure changes are made in one place (`setup.yaml`, `networks/`), never per instance.

```yaml
# config/instances/vm-dev.yaml
extends: [ ../networks/example.yaml ]   # the network of this machine
kernel:
  domain: "proxtop.dev.example.com"
  ip: "192.0.2.75"
provisioning:
  auto_port_start: 27500                # several instances on one machine: other ports
```

Choose it once per installation; it is remembered in `project/.instance` (not versioned):

```bash
./mercy install --instance=vm-dev
./mercy update                          # uses vm-dev
./mercy config --instance=vm-prod       # only a look at another instance
```

As soon as `config/instances/` contains files, every command needs an instance. Layers and options: see the
[scaffold README](https://github.com/aklebe-laravel/mercy-scaffold#project-repository).

## Production

1. Set the release number in `config/build.yaml` (`kernel.version`) and set `git.registry_namespace` in
   `config/setup.yaml` to the owner of your image.
2. Build and push the image in the dev folder: `./mercy build`.
3. On the server, install it with the [Mercy-Installer](https://github.com/aklebe-laravel/mercy-installer), which uses
   your copy of this repository as `project/` (stage `prod`, release `kernel.version` of `config/prod.yaml` or the
   instance).

## License

MIT, see [LICENSE.md](LICENSE.md).
