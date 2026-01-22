import re
from flask import abort

def validate_content(s: str) -> str:
    s = (s or "").strip()
    if not (1 <= len(s) <= 50):
        abort(400, "Invalid length")
    if not re.fullmatch(r"[A-Za-zÀ-ÖØ-öø-ÿ '\-]+", s):
        abort(400, "Invalid characters")
    return s

def validate_date(s: str) -> str:
    s = (s or "").strip()
    if not re.fullmatch(r"\d{2}/\d{2}/\d{4}", s):
        abort(400, "Invalid date format")
    return s