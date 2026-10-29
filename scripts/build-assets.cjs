// npm ci && npm run build; PHP uses fresh generated files and falls back to source.
const fs = require('node:fs');
const path = require('node:path');
const esbuild = require('esbuild');
process.chdir(path.resolve(__dirname, '..'));
const pages = ['index', 'aboutUs', 'professions', 'references', 'reference', 'clients', 'contact', 'legalNotices', 'privacyPolicy'];
const sources = pages.map(name => fs.readFileSync(`${name}.php`, 'utf8'));
const css = new Set(sources.flatMap(source => [...source.matchAll(/apsiAsset\('([^']+\.css)'\)/g)].map(match => match[1].slice(1))));
css.add('css/fonts.css');
for (const file of [...css, 'js/navigation.js', 'js/site.js', 'js/references.js']) {
    const isCss = file.endsWith('.css');
    const result = esbuild.transformSync(fs.readFileSync(file, 'utf8'), { loader: isCss ? 'css' : 'js', minify: true, legalComments: 'eof' });
    fs.writeFileSync(file.replace(/\.(css|js)$/, '.min.$1'), result.code);
}
sources.push(...['partials/siteHeader.php', 'partials/siteFooter.php'].map(file => fs.readFileSync(file, 'utf8')));
const names = new Set([...sources.join('\n').matchAll(/data-lucide="([a-z0-9-]+)"/g)].map(match => match[1]));
// The project details table also selects icons on the server.
for (const icon of ['map-pin', 'building-2', 'check-circle-2', 'calendar-days', 'user-round-cog', 'pencil-ruler', 'users', 'euro', 'layers-3', 'users-round', 'layout-grid']) names.add(icon);
const icons = [...names].map(name => name.split('-').map(part => part[0].toUpperCase() + part.slice(1)).join(''));
esbuild.buildSync({
    stdin: { contents: `import {createIcons,${icons.join(',')}} from 'lucide';window.lucide={createIcons:()=>createIcons({icons:{${icons.join(',')}}})};`, resolveDir: process.cwd() },
    bundle: true, minify: true, format: 'iife', outfile: 'js/icons.min.js', legalComments: 'eof'
});
console.log('Built public CSS, scripts and selected icons.');
