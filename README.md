# proxtop-web

**ProxTop** is a web desktop for Proxmox servers: VM cards with status and start/stop (with a grace period),
schedules, ACL per VM group, WireGuard member management on pfSense, LDAP login.
It is built with the [Mercy Scaffold](https://github.com/aklebe-laravel/mercy-scaffold) (Laravel 13, Livewire 4)
and the public modules [central-ui-module](https://github.com/aklebe-laravel/central-ui-module) and
[central-ui-theme](https://github.com/aklebe-laravel/central-ui-theme).

This repository is the **project template**: config and example business imports. Copy it, put in your values,
keep your copy private (it will contain your hosts and network).

## Quick start (dev)

```
git clone https://github.com/aklebe-laravel/mercy-scaffold.git proxtop
cd proxtop
git clone https://github.com/aklebe-proxtop/proxtop-web.git project
cp project/config/secrets.example.yaml project/config/secrets.yaml
# replace the example values in project/config/*.yaml and project/business_imports
composer install
./mercy install
./mercy config --all        # shows every value and where it comes from
```

Then your own repository: `git -C project remote set-url origin <your-private-repo>` and push.

## What to change

| File                                         | Content                                                                  |
|----------------------------------------------|--------------------------------------------------------------------------|
| `config/setup.yaml`                          | the product: name, image, theme, modules, pfSense URL                    |
| `config/networks/example.yaml`               | your network: own git server, DNS, NPM, LDAP                             |
| `config/dev.yaml`, `build.yaml`, `prod.yaml` | the instance: domain, release number, ports                              |
| `config/secrets.yaml`                        | pfSense API key, NPM/LDAP password, Proxmox token (`env`), not versioned |

The files contain only what differs from the Mercy defaults (`./mercy config --all` shows all keys and
where each value comes from), so a new Mercy version brings new settings without changing these files.
| `business_imports/.../proxtop_servers.php`            | your Proxmox server (s) and VMs |
| `business_imports/.../proxtop_property_templates.php` | VM groups, permissions and schedules |
| `business_imports/.../users.php`                      | groups and desktop items of your users |
| `business_imports/.../proxtop_wireguard_networks.php` | WireGuard networks (pfSense tunnels)                   |

Run the business imports after the install (and again after changing them):

```
sail artisan ldap:sync-all      # only with LDAP
sail artisan deploy-env:terraform-modules --module_name=local:business-imports --force
```

## Instances

One installation (a folder on a machine) is one instance: a few lines in `config/instances/<name>.yaml`,
everything else is inherited. The structure is changed in one place (`setup.yaml`, `networks/`), never per instance.

```yaml
# config/instances/vm-dev.yaml
extends: [ ../networks/example.yaml ]   # the network of this machine
kernel:
  domain: "my-product.dev.example.com"
  ip: "192.0.2.75"
provisioning:
  auto_port_start: 27500                # several instances on one machine: other ports
```

Choose it once per installation, it is remembered in `project/.instance` (not versioned):

```
./mercy install --instance=vm-dev
./mercy update                    # uses vm-dev
./mercy config --instance=vm-prod # only a look at another instance
```

Order: Mercy defaults → `setup.yaml` → `<stage>.yaml` → the instance (with its network) → `secrets.yaml`.
As soon as `config/instances/` has files, every command needs an instance (no run with the wrong config).

## Production

Build the image (`./mercy build`, release number in `config/build.yaml`) and install it on the server
with the [Mercy-Installer](https://github.com/aklebe-laravel/mercy-installer), which uses this project as `project/`.

## License

MIT, see [LICENSE.md](LICENSE.md).
