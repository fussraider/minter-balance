=== Minter Balance ===
Contributors: fussraider
Donate link: https://minterscan.net/address/Mx8e6c210b6f310ce8e38024838becca67cb52a428
Tags: Minter, blockchain, BIP
Requires at least: 5.2.2
Tested up to: 5.2.2
Requires PHP: 7.2
Stable tag:
License: MIT
License URI: https://raw.githubusercontent.com/fussraider/minter-balance/master/LICENSE

Плагин добавляет 2 шорткода и 2 виджета в WordPress, позволяющие выводить баланс кошелька сети Minter, в т.ч. в определенной монете с возможностью кеширования

== Description ==

После установки, необходимо произвести настройку плагина. Для этого необходимо перейти в "Настройки" => "Minter Balance"
и указать адрес API ноды, например https://api.minter.stakeholder.space/ и срок жизни кеша в секундах, например 60.
Если кеширование не требуется - укажите 0


Оба шорткода принимают на вход 3 параметра:
 - `address` - адрес отслеживаемого кошелька в сети Minter
 (по-умолчанию `Mx8e6c210b6f310ce8e38024838becca67cb52a428`)
 - `coin` - тикер монеты, баланс которой будет отслеживаться
 (по-умолчанию `BIP`)
 - `round` - параметр откругления дробной части. Целое число, >= 0.
 Если < 0 то округление не производится (по-умолчанию `-1`)

1. Шорткод `minter-balance-value` - возвращает только значение баланса

    Варианты использования

    `[minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428"]``
    `[minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="KARMA"]`
    `[minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="BIP" round="4"]`
    `[minter-balance-value address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" round="0"]`


2. Шорткод `minter-balance` - возвращает значение баланса вместе с тикером

    Варианты использования

    `[minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428"]`
    `[minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="KARMA"]`
    `[minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" coin="BIP" round="4"]`
    `[minter-balance address="Mx8e6c210b6f310ce8e38024838becca67cb52a428" round="0"]`

3. Виджет `Minter Balance Widget` выводит баланс всех монет на указанном в настройках адресе.
4. Виджет `Minter Balance Single Widget` выводит баланс одной монеты на адресе, по указанным данным в настройках виджета

В каждом экземпляре виджета можно указать индивидуальные параметры, в т.ч. параметр округления