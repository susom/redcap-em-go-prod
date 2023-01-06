# Go-Prod Plugin Refactor

This EM is a replacement for Go-Prod plugin.

### As a Developer can I add new Rule?

Yes, This Module allows developer to add new custom rules.

### How to add a new Rule?

1. Create a Rule class under [Rules folder](https://github.com/susom/redcap-em-go-prod/tree/main/classes/Rules)
2. The new class MUST implement interface `ValidationsImplementation` . *Classes not implementing the interface will be
   ignored by the EM*.
3. Define your rule notifications
   in [Language folder](https://github.com/susom/redcap-em-go-prod/blob/main/language/notifications.ini)
4. Class name must be in `snake_case` format.
3. At least the class must has the following methods *(You can more methods if needed)*:
    1. `getProject`
    2. `setProject`
    3. `validate`
    4. `getErrorMessage`
    5. `getNotifications`
    6. `setNotifications`
7. Please Note you do not have to call `setProject` and `setNotifications` as they are called by default when rule is
   enabled.
8. `validate` method will contain the validation logic. The method will return `true` if validation passed. `false` if
   validation failed.
9. If validation failed method `getErrorMessage` will be called. which will return an array with following parameter.
    1. `title` will be pulled from notification.ini file.
    2. `body` will be pulled from notification.ini file.
    3. `type` can be one of following: DANGER, WARNING, INFO.
    4. `modal` If you want user to open a modal this will be an array of modal table rows.
    5. `modalHeader` modal table header.
    6. `extra` custom text or html to be displayed.
    7. `links` array of links.
10. When your class is ready you need to tell the EM about it in `config.json`.
    1. Add a new checkbox to enable/disable the rule under system settings section.
        1. `{
           "key": "[YOUR_RULE_CLASSNAME]", <-- THIS MUST MATCH YOUR RULE CLASSNAME.
           "name": "[DESCRIBTION ABOUT YOUR RULE]",
           "required": false,
           "type": "checkbox"
           }`
    2. add `[YOUR_RULE_CLASSNAME]` under `auth-ajax-actions`.

#### Note: Make sure to enable your rule from your REDCap control panel. By default, rules are disabled.

Here is a new rule class example:  [link](https://github.com/susom/redcap-em-go-prod/tree/main/sample/)
