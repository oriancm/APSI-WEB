from pathlib import Path

p = Path('css/style.css')
css = p.read_text(encoding='utf-8')
start = css.index('@media', css.index('/* Mobile and tablet homepage. */'))
opening = css.index('{', start)
depth = 1
end = opening + 1
while depth:
    depth += (css[end] == '{') - (css[end] == '}')
    end += 1
body = css[opening + 1:end - 1]
chunks = []
cursor = 0
while cursor < len(body):
    next_open = body.find('{', cursor)
    if next_open < 0:
        chunks.append(body[cursor:])
        break
    depth = 1
    rule_end = next_open + 1
    while depth:
        depth += (body[rule_end] == '{') - (body[rule_end] == '}')
        rule_end += 1
    selector = body[cursor:next_open]
    if 'site-header' not in selector:
        chunks.append(body[cursor:rule_end])
    cursor = rule_end
css = css[:opening + 1] + ''.join(chunks) + css[end - 1:]
# The final mobile block only overrides the header while scrolling; shared rules own it now.
marker = '@media (max-width: 980px) {'
start = css.rfind(marker)
if 'site-header.is-scrolled' in css[start:]:
    css = css[:start]
p.write_text(css, encoding='utf-8')
