# Architecture: Pagerfanta

## Purpose

Pagerfanta is a PHP pagination library. It provides a `Pagerfanta` object that wraps any data source via an adapter, handles page/per-page logic, and renders pagination controls via pluggable view classes.

## Directory Structure

```
lib/
  Core/
    Pagerfanta.php              Primary pagination object (current page, max per page, slices)
    Pagerfanta_Interface.php    Contract for the paginator
    Adapter/
      Adapter_Interface.php     Contract: getNbResults() + getSlice(offset, length)
      Array_Adapter.php         Wraps a PHP array
      Callback_Adapter.php      Wraps arbitrary count/slice callables
      Concatenation_Adapter.php Combines multiple adapters
      Fixed_Adapter.php         Fixed count + pre-sliced results
      Null_Adapter.php          Known count, no data loading
      Transforming_Adapter.php  Post-processes results from an inner adapter
    Exception/                  Typed exceptions (out of range, invalid page, etc.)
    RouteGenerator/             Generates page URLs from a route name + parameters
    View/                       Renders HTML pagination controls
      Template/                 Bootstrap 3/4/5, Foundation, SemanticUI templates
  Adapter/Doctrine/
    ORM/Query_Adapter.php       Doctrine ORM query paginator
    DBAL/Query_Adapter.php      Doctrine DBAL query builder paginator
    Collections/                Doctrine Collection/Selectable adapters
    MongoDBODM/                 MongoDB ODM adapters
    PHPCRODM/                   PHPCR-ODM adapters
  Adapter/Elastica/             Elastica (Elasticsearch) adapter
  Adapter/Solarium/             Solarium (Solr) adapter
  Twig/                         Twig extension + view for template-based rendering
```

## Key Design Decisions

- **Adapter pattern**: Any data source implements a two-method interface (`getNbResults`, `getSlice`). The `Pagerfanta` object never knows how data is stored.
- **Immutable page boundaries**: Requesting an out-of-range page throws a typed exception rather than silently clamping, enabling 404 responses.
- **Template-based views**: HTML rendering is separated into a `TemplateInterface` (markup) and a `View` (logic). Templates are swappable without changing view logic.

## Extension Points

- Implement `Adapter_Interface` to paginate any custom data source.
- Implement `View_Interface` to add custom HTML rendering.
- Implement `Template_Interface` to customise pagination markup for an existing view.

## Dependency Flow

```
Application
  -> Pagerfanta(adapter)
  -> setCurrentPage(n), setMaxPerPage(m)
  -> getCurrentPageResults()  -> adapter->getSlice(offset, length)
  -> getNbPages()             -> adapter->getNbResults()
  -> View::render(pagerfanta, routeGenerator, options)  -> HTML string
```
