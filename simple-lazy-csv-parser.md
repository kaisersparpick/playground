## Simple lazy CSV parsing in Rakudo

**The parser**
```pl6
my $filename = 'sample.csv';

# comma delimited list of digits and quoted strings:
my $csv-pattern = / ^ ( '"' <-["]> * '"' || \d+ ) ** 1..* % ',' $ /;

for lazy $filename.IO.lines.skip -> $line {
  $line ~~ $csv-pattern;
  say $/;
}
```

**Input** - "sample.csv"
```csv
"email","name","age","country"
"uncle@sam.com","Uncle Sam",23,"US"
"john@bull.co.uk","John Bull",34,"UK"
```

**Output**
```pl6
｢"uncle@sam.com","Uncle Sam",23,"US"｣
 0 => ｢"uncle@sam.com"｣
 0 => ｢"Uncle Sam"｣
 0 => ｢23｣
 0 => ｢"US"｣
｢"john@bull.co.uk","John Bull",34,"UK"｣
 0 => ｢"john@bull.co.uk"｣
 0 => ｢"John Bull"｣
 0 => ｢34｣
 0 => ｢"UK"｣
```
