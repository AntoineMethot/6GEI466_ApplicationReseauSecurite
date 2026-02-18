// utils.js
const fs = require("fs");
const path = require("path");

function escapeHtml(s) {
  return String(s)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function send404(res, reqPath) {
  res.status(404).type("html");
  const tpl = fs.readFileSync(
    path.join(__dirname, "templates", "erreur404.html"),
    "utf-8"
  );
  res.send(tpl.replace("{{ path }}", escapeHtml(reqPath)));
}

function zodiacSignFromDate(d) {
  const m = d.getMonth() + 1; // 1..12
  const day = d.getDate();

  const map = {
    1:  ["Capricorn", 19, "Aquarius"],
    2:  ["Aquarius", 18, "Pisces"],
    3:  ["Pisces", 20, "Aries"],
    4:  ["Aries", 19, "Taurus"],
    5:  ["Taurus", 20, "Gemini"],
    6:  ["Gemini", 20, "Cancer"],
    7:  ["Cancer", 22, "Leo"],
    8:  ["Leo", 22, "Virgo"],
    9:  ["Virgo", 22, "Libra"],
    10: ["Libra", 22, "Scorpio"],
    11: ["Scorpio", 21, "Sagittarius"],
    12: ["Sagittarius", 21, "Capricorn"],
  };

  const [s1, cut, s2] = map[m];
  return (day <= cut) ? s1 : s2;
}

function parseDateYMD(s) {
  if (!/^\d{4}-\d{2}-\d{2}$/.test(s)) return null;
  const [yy, mm, dd] = s.split("-").map(Number);
  const d = new Date(yy, mm - 1, dd);

  // Reject invalid dates (e.g., 2025-02-31)
  if (d.getFullYear() !== yy || d.getMonth() !== (mm - 1) || d.getDate() !== dd) return null;
  return d;
}

module.exports = {
  send404,
  zodiacSignFromDate,
  parseDateYMD,
};
