# oz-bold
BOLD barcodes as linked data

## Upload

```
curl http://130.209.46.63/blazegraph/sparql?context-uri=http://boldsystems.org -H 'Content-Type: text/rdf+n3' --data-binary '@bold.nt'  --progress-bar | tee /dev/null
```

