<?php
/**
 * Write extra Traefik dynamic routers for warmtepomp campaign domains.
 *
 * Run on the Coolify host:
 * php scripts/write-traefik-extra-domains.php
 */

$domains = array(
    'warmtepompzonderboiler.nl',
    'warmtepompamstelveen.nl',
    'warmtepompaalsmeer.nl',
    'warmtepompbadhoevedorp.nl',
    'warmtepompdiemen.nl',
    'warmtepompouderkerk.nl',
    'warmtepompuithoorn.nl',
    'warmtepomphoofddorp.nl',
    'warmtepompnieuwvennep.nl',
    'warmtepomppurmerend.nl',
    'warmtepompkennemerland.nl',
    'warmtepompzaanstreek.nl',
    'warmtepompheiloo.nl',
    'warmtepompcastricum.nl',
    'warmtepompbloemendaal.nl',
    'warmtepompzandvoort.nl',
    'warmtepompijmuiden.nl',
    'warmtepompbeverwijk.nl',
    'warmtepompheerhugowaard.nl',
    'warmtepomplandsmeer.nl',
    'warmtepompwormerland.nl',
    'warmtepompbussum.nl',
    'warmtepompnaarden.nl',
    'warmtepomphuizen.nl',
    'warmtepomplaren.nl',
    'warmtepompblaricum.nl',
    'warmtepompweesp.nl',
    'warmtepompgooi.nl',
    'warmtepomplelystad.nl',
    'warmtepompdronten.nl',
    'warmtepompgouda.nl',
    'warmtepompdelft.nl',
    'warmtepompnieuwegein.nl',
    'warmtepompzoetermeer.nl',
    'warmtepompamersfoort.nl',
    'warmtepompdordrecht.nl',
    'warmtepomphoorn.nl',
    'warmtepompden-helder.nl',
    'warmtepompapeldoorn.nl',
    'warmtepompzeist.nl',
    'warmtepompachterhoek.nl',
    'warmtepompveluwe.nl',
    'warmtepompdenbosch.nl',
);

$hosts = array();
foreach ($domains as $domain) {
    $hosts[] = $domain;
    $hosts[] = 'www.' . $domain;
}

$yaml = "http:\n";
$yaml .= "  middlewares:\n";
$yaml .= "    warmtepomp-extra-gzip:\n";
$yaml .= "      compress: {}\n";
$yaml .= "    warmtepomp-extra-redirect-to-https:\n";
$yaml .= "      redirectScheme:\n";
$yaml .= "        scheme: https\n";
$yaml .= "  routers:\n";

foreach ($hosts as $i => $host) {
    $name = 'warmtepomp-extra-' . preg_replace('/[^a-z0-9]+/', '-', strtolower($host));
    $yaml .= "    {$name}-http:\n";
    $yaml .= "      entryPoints:\n";
    $yaml .= "        - http\n";
    $yaml .= "      rule: \"Host(`{$host}`) && PathPrefix(`/`)\"\n";
    $yaml .= "      middlewares:\n";
    $yaml .= "        - warmtepomp-extra-redirect-to-https\n";
    $yaml .= "      service: warmtepomp-extra-wordpress\n";
    $yaml .= "    {$name}-https:\n";
    $yaml .= "      entryPoints:\n";
    $yaml .= "        - https\n";
    $yaml .= "      rule: \"Host(`{$host}`) && PathPrefix(`/`)\"\n";
    $yaml .= "      middlewares:\n";
    $yaml .= "        - warmtepomp-extra-gzip\n";
    $yaml .= "      service: warmtepomp-extra-wordpress\n";
    $yaml .= "      tls:\n";
    $yaml .= "        certResolver: letsencrypt\n";
}

$yaml .= "  services:\n";
$yaml .= "    warmtepomp-extra-wordpress:\n";
$yaml .= "      loadBalancer:\n";
$yaml .= "        servers:\n";
$yaml .= "          - url: \"http://wordpress-eao5oq3va188f5po0d0aax79:80\"\n";

$target = '/data/coolify/proxy/dynamic/warmtepomp-campaign-extra.yaml';
if (file_put_contents($target, $yaml) === false) {
    fwrite(STDERR, "Could not write {$target}\n");
    exit(1);
}

echo "Wrote {$target} with " . count($hosts) . " hosts.\n";
