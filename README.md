## WordPress Minter Balance plugin

Плагин добавляет 2 шорткода в WordPress, позволяющие выводить 
баланс кошелька сети Minter в определенной монете с возможностью 
кеширования

После установки, необходимо произвести настройку плагина. Для этого 
необходимо перейти в "Настройки" => "Minter Balance" и указать адрес 
API ноды, например 
[https://api.minter.stakeholder.space/](https://api.minter.stakeholder.space/)
и срок жизни кеша в секундах, например 60. Если кеширование не требуется 
- укажите 0

Оба шорткода принимают на вход 3 параметра:
 - `address` - адрес отслеживаемого кошелька в сети Minter 
 (по-умолчанию `Mx8e6c210b6f310ce8e38024838becca67cb52a428`)
 - `coin` - тикер монеты, баланс которой будет отслеживаться 
 (по-умолчанию `BIP`)
 - `round` - параметр откругления дробной части. Целое число, >= 0. 
 Если < 0 то округление не производится (по-умолчанию `-1`)

1. Шорткод `minter-balance-value` - возвращает только значение баланса
    
    Варианты использования
    ```php
    [minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428"]
    [minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="KARMA"]
    [minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="BIP" round="4"]
    [minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" round="0"]
    ```

2. Шорткод `minter-balance` - возвращает значение баланса вместе с тикером

    Варианты использования
    ```php
    [minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428"]
    [minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="KARMA"]
    [minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="BIP" round="4"]
    [minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" round="0"]
    ```
    
## TODO

- Исправить округление очень малых значений (могут возвращаться строки вида `7.96275E-5`)
- Обработчик ответа ноды с ошибкой
- При выключении плагина удалять весь его кеш
- Переодически чистить кеш

---

О багах, пожеланиях или недочетах - пишите мне в телеграм `@fussraider`.

Donate: [Mx8e6c210b6f310ce8e38024838becca67cb52a428](https://minterscan.net/address/Mx8e6c210b6f310ce8e38024838becca67cb52a428)