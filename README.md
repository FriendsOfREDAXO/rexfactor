# rexfactor

Adds automated code upgrades to REDAXO improving developer productivity and code quality.

The AddOn integrates [rector](https://github.com/rectorphp/rector) with the developer in mind, meaning it eases use for often used migration/upgrade use-cases. If you are a rector expert you don't need this AddOn. 

Primary purpose is to allow people less experienced with developer tooling to automate migration tasks.

Users apply the changes after a preview in a diff view.

## use cases

### PHP Version Migrations

This use case helps updating the PHP version used in a project to a newer one according to the office php.net migration guide. This can involve migrating code to be compatible with the new version and updating any deprecated features to the recommended replacements.


### Improve Code Quality

- `Unify Code Quality:` In this use case, the goal is to ensure that all code in a project adheres to the same coding standards and best practices.

- `Remove Dead Code:` This use case involves identifying and removing code that is no longer used or needed in the codebase.

- `Infer Type Declarations:` In this use case, the goal is to automatically infer native return-types or parameter-types of methods&functions in the codebase.

- `Reduce Symbol Visibility (Privatization):` This use case involves reducing the visibility of symbols in the codebase to ease future refactoring and reduce the chance of unintended use. 

-  `Use Early Returns:` In this use case, the goal is to reduce the complexity of code by using early returns.

### PHPUnit Version Migrations: 

This use cases involve updating the version of PHPUnit used in a project to a newer one. This can involve migrating test code to be compatible with the new version and updating any deprecated features to the recommended replacements.

These migrations are only available to AddOns which contain a `tests/` folder.

### Improve Test-Code Quality: 

This use cases involve improving the quality of test code by making it more maintainable, readable, and efficient. This can include refactoring existing test code to follow best practices, removing duplication, and improving the structure of test suites.

These migrations are only available to AddOns which contain a `tests/` folder.

### Misc 

- `REDAXO Specific Code Style:` This use case ensures that code written follows the [REDAXO code style guidelines](https://github.com/redaxo/php-cs-fixer-config). This can include adhering to naming conventions, using appropriate formatting, and following the coding standards recommended by the REDAXO community.

- `More Explicit Coding Style:` This use case involves ensuring that code is written in a more explicit and clear manner, so that it is easier to read and maintain. This can include using more descriptive variable names, avoiding ambiguous function names, and using appropriate comments.



### 

