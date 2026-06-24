# Repository Rules

Always:
- **Reuse existing abstractions first**: Do not reinvent database connections, routes, or layout mechanisms.
- **Follow current architecture**: Maintain clean PHP file routing alongside clean CSS responsive structures.
- **Preserve naming conventions**: Match the class names, folder names, and variable structures already established in the codebase.
- **Inspect existing utilities before creating new ones**: Check folders like `functions/` or `admin/` before drafting new helper scripts.
- **Prefer incremental changes over rewrites**: Focus on surgical, precise updates rather than refactoring healthy systems.
- **Preserve type safety**: Handle variables, SQL execution params, and return types correctly.
- **Avoid introducing dependencies unless necessary**: Keep the app dependency-free unless explicitly requested.

Before coding:
1. Inspect relevant modules.
2. Understand current patterns.
3. Explain intended integration strategy.

Never:
- Duplicate functionality.
- Introduce parallel architectures.
- Silently rewrite large systems.
- Bypass existing API layers.
- Add state management libraries without justification.
