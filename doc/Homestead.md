# Homestead settings.

Settings for your `Homestead.yaml` file.

```yaml
folders:
    - map: ~/Projects
      to: /home/vagrant/Projects

sites:
    - map: minesweeper.test
      to: /home/vagrant/Projects/Deviget/minesweeper-API/public
      # Optional.
      type: "laravel"
      php: "7.4"

databases:
    - minesweeper

```
