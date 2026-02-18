const express = require("express");
const fs = require("fs");
const path = require("path");
const HOROSCOPES = require("./horoscopes");

const app = express();
const PORT = 8000;

const { parseDateYMD, zodiacSignFromDate, send404 } = require("./utils");

app.use(express.urlencoded({ extended: true }));
app.use("/static", express.static(path.join(__dirname, "static")));


// GET /
app.get("/", (req, res) => {
  const tpl = fs.readFileSync(path.join(__dirname, "templates", "accueil.html"), "utf-8");
  const html = tpl.replace("{{ titre }}", "HOROSCOPE");
  res.type("html").send(html);
});

// POST /horoscope
app.post("/horoscope", (req, res) => {
  const prenom = (req.body.prenom || "").trim();
  const nom = (req.body.nom || "").trim();
  const dateS = (req.body.date || "").trim();

  if (!prenom || !nom || !dateS) {
    return res.status(400).type("text").send("parametre manquant");
  }

  const d = parseDateYMD(dateS);
  if (!d) {
    return res.status(400).type("text").send("date invalide");
  }

  const sign = zodiacSignFromDate(d);
  const entry = HOROSCOPES[sign];
  if (!entry) {
    return res.status(500).type("text").send("Horoscope not found");
  }

  return res.json({
    prenom,
    nom,
    sign,
    image: entry.image,
    text: entry.text,
  });
});

// 4) Everything else -> 404 template
app.use((req, res) => {
  send404(res, req.path);
});

app.listen(PORT, "127.0.0.1", () => {
  console.log(`Node server running at http://127.0.0.1:${PORT}`);
});
