# class-visibility

## Private and protected classes for PHP.

Private and protect classes known from some strongly typed languages helps keep cleaner dependencies and promotes good
code organization.

### Private classes

Private classes can only be used by classes defined inside the exact same namespace. Ex. class
```SomeNamespace\Abc\SomeClassName``` can be used by ```SomeNamespace\Abc\OtherClass``` but **not** by
```SomeNamespace\Abc\NestedNamespace\SomeClassFromNestedNamespace```, and cannot be used by any class from outside
of ```SomeNamespace\Abc``` namespace.

### Protected classes

Protected classes work slightly differently than in Java. Protected class can be used inside the exact same namespace,
like private class, but it also can be used by classes from nested namespaces. Ex. class
```SomeNamespace\Abc\SomeClassName``` can be used by ```SomeNamespace\Abc\OtherClass``` **and** by
```SomeNamespace\Abc\NestedNamespace\SomeClassFromNestedNamespace```

### Declaring visibility

All you need to do to declare class visibility is to add the attribute.

```php
use Lukasz93P\ClassVisibility\Visibility\Visibilities\PrivateVisibility;

#[PrivateVisibility(self::class)]
class ClassWithPrivateVisibility
{
}
```

```php
use Lukasz93P\ClassVisibility\Visibility\Visibilities\ProtectedVisibility;

#[ProtectedVisibility(self::class)]
class ClassWithProtectedVisibility
{
}
```

**Classes with no visibility attribute defined are considered public.**

### Check violations

To check if a given namespace violates visibility rules run

```shell
vendor/bin/class-visibility check <namespace to check>
```
or
```shell
vendor/bin/class-visibility check <namespace to check>,<another namespace>,<next namespace>
```

