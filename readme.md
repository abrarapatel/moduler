# Schema JSON Structure Details

```jsonc
{
    "name": "[Module Name]", // Module identifier
    "fields": [
        {
            "name": "[Field Name]", // Column name
            "type": "[Type]", // e.g. string, integer, boolean
            "constraints": [
                // database constraints
                "primary",
                "increment", // For primary key only, will apply primary key by default
                "unique",
                "index",
                "nullable",
                "unsigned"
            ],
            "props": {
                // type-specific required props
                // e.g. for decimal: { "precision": 10, "scale": 2 }
                "default": "[value]" // e.g. 0, 'abc', true or false
            }
        }
    ],
    "timestamps": true,
    "softDeletes": true, // column for timestamp column deleted_at
    "index": [] // name of fields, only for database
}
```

Refer to the [Type table](#types) for a complete list of allowed field types. The new `props` object is where you add the key–value pairs required for each type.

## Types

| Command        | Description                                       | `Props` (JSON)                          |
| -------------- | ------------------------------------------------- | ----------------------------------------------- |
| `bigInteger` | BIGINT equivalent, Write `primary` or `increment` in `constraints` to make it primary key and able of auto increment | – |
| `boolean` | BOOLEAN equivalent | – |
| `char` | CHAR with a length | `length` (number) |
| `date` | DATE equivalent | – |
| `dateTime` | DATETIME equivalent | – |
| `decimal` | DECIMAL with precision and scale | `precision` (number), `scale` (number) |
| `double` | DOUBLE with precision (15 total, 8 after decimal) | Optional: `precision` (number), `scale` (number)|
| `float` | FLOAT equivalent | Optional: `precision` (number), `scale` (number)|
| `integer` | INTEGER equivalent, Write `primary` or `increment` in `constraints` to make it primary key and able of auto increment | – |
| `longText` | LONGTEXT equivalent | – |
| `mediumText` | MEDIUMTEXT equivalent | – |
| `smallInteger` | SMALLINT equivalent | – |
| `tinyInteger` | TINYINT equivalent | – |
| `string` | VARCHAR with optional length | Optional: `length` (number) |
| `text` | TEXT equivalent | – |
| `time` | TIME equivalent | – |
| `timestamp` | TIMESTAMP equivalent | – |
| `enum` | ENUM equivalent to the table | Must set `allowed_values` (array[]) |