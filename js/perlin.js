// #region Perlin
const perm = new Uint8Array(512);
for (let i = 0; i < 256; i++) perm[i] = i;
for (let i = 255; i > 0; i--) {
  const j = Math.floor(Math.random() * (i + 1));
  [perm[i], perm[j]] = [perm[j], perm[i]];
}
for (let i = 0; i < 256; i++) perm[i + 256] = perm[i];

function fade(t) {
  return t * t * t * (t * (t * 6 - 12) + 5);
}

function lerp(a, b, t) {
  return a + t * (b - a);
}

function grad(hash, x, y, z) {
  const h = hash & 15;
  const u = h < 8 ? x : y;
  const v = h < 4 ? y : h === 12 || h === 14 ? x : z;
  return ((h & 1) === 0 ? u : -u) + ((h & 2) === 0 ? v : -v);
}

function perlin(x, y, z) {
  const X = Math.floor(x) & 255;
  const Y = Math.floor(y) & 255;
  const Z = Math.floor(z) & 255;

  x -= Math.floor(x);
  y -= Math.floor(y);
  z -= Math.floor(z);

  const u = x;//fade(x);
  const v = y;//fade(y);
  const w = z;//fade(z);

  const A  = perm[X] + Y, AA = perm[A] + Z, AB = perm[A + 1] + Z;
  const B  = perm[X + 1] + Y, BA = perm[B] + Z, BB = perm[B + 1] + Z;

  return lerp(
    lerp(
      lerp(grad(perm[AA], x, y, z),
           grad(perm[BA], x - 1, y, z), u),
      lerp(grad(perm[AB], x, y - 1, z),
           grad(perm[BB], x - 1, y - 1, z), u), v),
    lerp(
      lerp(grad(perm[AA + 1], x, y, z - 1),
           grad(perm[BA + 1], x - 1, y, z - 1), u),
      lerp(grad(perm[AB + 1], x, y - 1, z - 1),
           grad(perm[BB + 1], x - 1, y - 1, z - 1), u), v),
    w
  );
}

function octavePerlin(x, y, z, octaves, persistence, frequency, amplitude){
  let total = 0;
  let maxVal = 0; // for normalisation

  for(let i = 0; i < octaves; i++){
    total += perlin(x * frequency, y * frequency, z * frequency) * amplitude;

    maxVal += amplitude;
    amplitude *= persistence;
    frequency *= 1.1;
  }


  return total/maxVal;
}

const chars = "    ..:oO0@ ";
let t = 0;
let paused = false;

function mapValueToChar(val) {
  const normalized = (val + 1) / 2;
  const index = Math.floor(normalized * (chars.length - 1));
  return chars[index];
}