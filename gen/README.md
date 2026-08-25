# gen/

GIR-driven generator (milestone 3). Reads `/usr/share/gir-1.0/*.gir`, emits
`src/gen/<Ns>/<Type>.{h,cpp}` plus `register_<Ns>()` in topological (parent-first)
order, `stubs/*.php`, and `docs/reference-objects.md`.

`overrides/<Type>.<method>.cpp` replaces a generated method body. Never hand-edit
files under `src/gen/`.
