# Architecture: SyliusGridBundle

## Purpose

Symfony bundle and companion component for building configurable admin data grids. Grids define fields, filters, actions, and sorting configuration; the bundle renders them via Twig and handles pagination, filtering, and sorting automatically.

## Directory Structure

```
src/
  Component/
    Definition/               Grid, Field, Filter, Action, ActionGroup value objects
    Configuration/            Config extender, sorting handler, removals handler
    Data/
      DataProvider.php        Fetches a page of grid data from a DataSource
      DataSource/             Per-driver data sources (ORM, DBAL, PHPCRODM)
    DataExtractor/
      PropertyAccessDataExtractor.php  Extracts cell values from entities via Symfony PropertyAccess
    FieldTypes/               DateTime, Enum, Twig field type renderers
    Filter/                   Boolean, Date, Entity, String, Money, etc. filter implementations
    Event/                    GridDefinitionConverterEvent
    Grid/                     Grid interface and attribute-based grid registration
    Registry/                 GridRegistry storing grid definitions by name
    Provider/                 ServiceGridProvider resolves grids from the registry
    Sorting/                  Sorting value objects

  Bundle/
    Sylius_Grid_Bundle.php
    DependencyInjection/
      SyliusGridExtension.php          Loads config, registers grid definitions
      Configuration.php                Config tree builder
      Compiler/
        Register_Drivers_Pass.php       Tags DataSource drivers
        Register_Field_Types_Pass.php   Tags FieldType renderers
        Register_Filters_Pass.php       Tags Filter implementations
        Register_Stub_Commands_Pass.php Maker bundle integration
        Register_Timezone_Parameter_Pass.php
    Builder/
      GridBuilder.php         Fluent API for programmatic grid definition
      Action/, ActionGroup/, Field/, Filter/  Builder sub-objects
    Doctrine/ORM/DBAL/PHPCRODM/  Framework-specific DataSource implementations
    FieldTypes/               Twig-based field type (renders Twig snippets)
    Renderer/
      TwigGridRenderer.php    Main Twig-based grid renderer
      TwigBulkActionGridRenderer.php
    Storage/
      SessionFilterStorage.php  Persists active filters in Symfony session
    Templating/Helper/        Grid and BulkAction Twig helpers
    Maker/                    Symfony MakerBundle stubs for generating grid classes
```

## Key Design Decisions

- **Definition separation**: Grid configuration (fields, filters, actions) is a pure value object graph (`Definition/Grid`, etc.), completely separate from rendering or data fetching.
- **Attribute grids**: PHP 8 attributes (`#[AsGrid]`) allow grids to be defined as plain classes with attributes rather than Yaml, enabling IDE autocompletion and refactoring.
- **Driver abstraction**: Data sources are swappable drivers (ORM, DBAL, PHPCR, custom). The `DataProvider` orchestrates sorting, filtering, and pagination against any driver.
- **Session filter storage**: Active filters are stored in the session by grid name + context, so filter state persists across pagination.

## Extension Points

- Implement a grid class with `#[AsGrid]` attribute or define grids in YAML.
- Register custom filter types tagged `sylius.grid_filter`.
- Register custom field types tagged `sylius.grid_field`.
- Register custom data source drivers tagged `sylius.grid_driver`.

## Dependency Flow

```
Request with ?page=2&criteria[name]=foo
  -> GridController / GridHelper
    -> GridRegistry::get('app_product_grid') -> GridDefinition
    -> DataProvider::getData(definition, parameters)
      -> DataSource driver filters by criteria, sorts, paginates
      -> returns DataSourceInterface (paginated result)
    -> TwigGridRenderer::render(grid, dataSource, parameters)
      -> renders rows via FieldType renderers
      -> renders filters, actions, pagination
      -> HTML
```
