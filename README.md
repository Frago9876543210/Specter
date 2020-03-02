# Specter
PocketMine plugin which allows create fake players

### Why?
For testing multiplayer, mini games, etc

### API
```php
Specter::getInstance()->createPlayer(new SpecterInfo("fake"));
```
_Note:_ starting with some version of PM4, booting of RakLib occurs later than plugins. You could create new player in `NetworkInterfaceRegisterEvent` with higher event priority or try to use `depends` in `plugin.yml`