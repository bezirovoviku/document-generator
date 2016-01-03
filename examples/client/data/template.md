{nadpis} - {$date | date *d-m-Y H:i:s* }
============

{$number|number eu 4}

{$date|date $format}

{if $condition}
Condition is **true**
{/if}

{foreach items as item}
  * {item.name}
{/foreach}