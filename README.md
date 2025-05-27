# REDAXO-AddOn: rexfactor

Adds automated code upgrades to REDAXO improving developer productivity and code quality.

The AddOn integrates [rector](https://github.com/rectorphp/rector) with the developer in mind, meaning it eases use for often used migration/upgrade use-cases. If you are a rector expert you don't need this AddOn. 

Primary purpose is to allow people less experienced with developer tooling to automate migration tasks.

Users apply the changes after a preview in a diff view.

## use cases

## Type Coverage and Refactorings

The quality of refactorings performed with rexfactor is highly dependent on the native type coverage of the code, which can be measured with rexstan. This relationship exists for several reasons:

1. **Safer Automated Changes**:  
   Code with high type coverage allows rexfactor (via Rector) to perform more precise and safer refactorings. Particularly, the "TYPE_DECLARATION" set yields better results when the code already has partial type information.

2. **Better Static Analysis**:  
   Type definitions are a fundamental building block for static code analysis. Tools like rexstan can only work reliably when they know which data types they're dealing with.

3. **Prevention of Regressions**:  
   When refactoring strongly typed code, potential issues can be detected earlier, which reduces the likelihood of regressions.

### Recommended Approach

1. First, measure the current type coverage with rexstan
2. Apply the "TYPE_DECLARATION" set with RexFactor
3. Run rexstan again to check the improved type coverage
4. Proceed with other desired refactorings that now build upon a more solid, typed codebase

This creates a positive cycle: Higher type coverage leads to better refactorings, which in turn can further improve type coverage.

### PHP Version Migrations

This use case helps updating the PHP version used in a project to a newer one according to the official php.net migration guide. This can involve migrating code to be compatible with the new version and updating any deprecated features to the recommended replacements.

Available migrations include PHP 7.2 through PHP 8.3, allowing you to gradually update your codebase to support newer PHP versions.


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

- `REDAXO Specific Code Style v1:` This use case ensures that code written follows the [REDAXO code style guidelines](https://github.com/redaxo/php-cs-fixer-config). This can include adhering to naming conventions, using appropriate formatting, and following the coding standards recommended by the REDAXO community. Compatible with all PHP versions.

- `REDAXO Specific Code Style v2:` An enhanced version of the REDAXO code style that utilizes PHP 8.1+ features. This includes improved type declarations, nullable type handling, property type conversion, and modern code structures. Requires PHP 8.1 or higher. You can customize the rules by editing the `custom-cs-rules.php` file in the addon directory.

- `More Explicit Coding Style:` This use case involves ensuring that code is written in a more explicit and clear manner, so that it is easier to read and maintain. This can include using more descriptive variable names, avoiding ambiguous function names, and using appropriate comments.




