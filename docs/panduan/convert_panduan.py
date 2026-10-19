#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script Konversi Buku Panduan SIM-LiBas (Markdown -> DOCX)
Menggunakan python-docx dengan styling profesional:
- Tipografi Segoe UI / Calibri
- Header Tabel Navy / Teal berlatar belakang warna dengan teks putih
- Callout Box berbingkai kiri untuk Alert/Note dan Slot Tangkapan Layar (Screenshot)
- Code / ASCII Block dengan font Consolas monospaced
- Penanganan format inline: Bold, Italic, Monospace, Link, dan <br>
"""

import os
import re
import sys
from pathlib import Path

import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import qn, nsdecls

# --- Konfigurasi Warna Tema ---
COLOR_PRIMARY = RGBColor(0x1E, 0x3A, 0x8A)      # Deep Navy #1E3A8A
COLOR_SECONDARY = RGBColor(0x0F, 0x76, 0x6E)    # Teal #0F766E
COLOR_TEXT_MAIN = RGBColor(0x1F, 0x29, 0x37)    # Charcoal Dark #1F2937
COLOR_TEXT_MUTED = RGBColor(0x4B, 0x55, 0x63)   # Gray #4B5563
COLOR_LINK = RGBColor(0x25, 0x63, 0xEB)         # Royal Blue #2563EB

HEX_PRIMARY = "1E3A8A"
HEX_SECONDARY = "0F766E"
HEX_GRAY_LIGHT = "F3F4F6"
HEX_GRAY_BORDER = "D1D5DB"
HEX_ALERT_NOTE_BG = "EFF6FF"
HEX_ALERT_NOTE_BORDER = "3B82F6"
HEX_ALERT_WARN_BG = "FFFBEB"
HEX_ALERT_WARN_BORDER = "F59E0B"
HEX_ALERT_IMP_BG = "FEF2F2"
HEX_ALERT_IMP_BORDER = "EF4444"
HEX_SLOT_BG = "F0F9FF"
HEX_SLOT_BORDER = "0284C7"

FONT_MAIN = "Segoe UI"
FONT_HEADING = "Segoe UI Semibold"
FONT_CODE = "Consolas"

def set_cell_background(cell, hex_color):
    """Mengatur warna latar belakang sel tabel."""
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=120, bottom=120, left=180, right=180):
    """Mengatur padding sel tabel (dalam dxa, 20 dxa = 1 pt)."""
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(
        f'<w:tcMar {nsdecls("w")}>'
        f'<w:top w:w="{top}" w:type="dxa"/>'
        f'<w:bottom w:w="{bottom}" w:type="dxa"/>'
        f'<w:left w:w="{left}" w:type="dxa"/>'
        f'<w:right w:w="{right}" w:type="dxa"/>'
        f'</w:tcMar>'
    )
    tcPr.append(tcMar)

def set_cell_left_border(cell, hex_color, size_eighth_pt=24):
    """Membuat bingkai tebal di sisi kiri sel saja (untuk callout box)."""
    tcPr = cell._tc.get_or_add_tcPr()
    tcBorders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'<w:top w:val="none"/>'
        f'<w:left w:val="single" w:sz="{size_eighth_pt}" w:space="0" w:color="{hex_color}"/>'
        f'<w:bottom w:val="none"/>'
        f'<w:right w:val="none"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(tcBorders)

def set_table_borders(table, hex_color=HEX_GRAY_BORDER):
    """Mengatur border standar tabel data."""
    tblPr = table._tbl.tblPr
    tblBorders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'<w:top w:val="single" w:sz="4" w:space="0" w:color="{hex_color}"/>'
        f'<w:left w:val="none"/>'
        f'<w:bottom w:val="single" w:sz="4" w:space="0" w:color="{hex_color}"/>'
        f'<w:right w:val="none"/>'
        f'<w:insideH w:val="single" w:sz="4" w:space="0" w:color="{hex_color}"/>'
        f'<w:insideV w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(tblBorders)

def make_callout_box(doc, bg_hex, border_hex):
    """Membuat tabel 1x1 sebagai kotak callout/alert berbingkai kiri."""
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    cell = tbl.cell(0, 0)
    cell.width = Inches(6.5)
    set_cell_background(cell, bg_hex)
    set_cell_left_border(cell, border_hex, size_eighth_pt=24)
    set_cell_margins(cell, top=140, bottom=140, left=200, right=200)
    return cell

def parse_inline_formatting(paragraph, text, default_size=10, default_color=COLOR_TEXT_MAIN, default_font=FONT_MAIN):
    """Mem-parsing penanda inline: **bold**, *italic*, `code`, [link](url), dan <br>."""
    if not text:
        return

    # Tokenizer regex
    token_re = re.compile(
        r'(\*\*[^*]+?\*\*|\*[^*]+?\*|`[^`]+?`|\[[^\]]+?\]\([^)]+?\)|<br\s*/?>)'
    )
    parts = token_re.split(text)

    for part in parts:
        if not part:
            continue
        
        # Line break <br>
        if re.match(r'^<br\s*/?>$', part, re.IGNORECASE):
            paragraph.add_run().add_break()
            continue
            
        # Bold **text**
        if part.startswith('**') and part.endswith('**') and len(part) >= 4:
            content = part[2:-2]
            run = paragraph.add_run(content)
            run.font.name = default_font
            run.font.size = Pt(default_size)
            run.font.color.rgb = default_color
            run.bold = True
            continue

        # Italic *text*
        if part.startswith('*') and part.endswith('*') and len(part) >= 2 and not part.startswith('**'):
            content = part[1:-1]
            run = paragraph.add_run(content)
            run.font.name = default_font
            run.font.size = Pt(default_size)
            run.font.color.rgb = default_color
            run.italic = True
            continue

        # Monospace `code`
        if part.startswith('`') and part.endswith('`') and len(part) >= 2:
            content = part[1:-1]
            run = paragraph.add_run(f" {content} ")
            run.font.name = FONT_CODE
            run.font.size = Pt(default_size * 0.92)
            run.font.color.rgb = RGBColor(0x99, 0x1B, 0x1B)  # Red-ish code highlight
            run.font.highlight_color = docx.enum.text.WD_COLOR_INDEX.GRAY_25
            continue

        # Link [label](url)
        link_m = re.match(r'^\[([^\]]+)\]\(([^)]+)\)$', part)
        if link_m:
            label = link_m.group(1)
            url = link_m.group(2)
            run = paragraph.add_run(label)
            run.font.name = default_font
            run.font.size = Pt(default_size)
            run.font.color.rgb = COLOR_LINK
            run.underline = True
            continue

        # Normal text
        run = paragraph.add_run(part)
        run.font.name = default_font
        run.font.size = Pt(default_size)
        run.font.color.rgb = default_color

def convert_md_file_to_docx(md_path, docx_path):
    """Mengonversi satu berkas markdown ke berkas DOCX terformat profesional."""
    print(f"Mengonversi: {md_path.name} -> {docx_path.name}")
    
    with open(md_path, 'r', encoding='utf-8') as f:
        lines = f.readlines()

    doc = docx.Document()

    # Atur Margin Halaman (Standar A4, 1 inch / 2.54 cm)
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)

    # State variables
    i = 0
    n = len(lines)
    
    while i < n:
        raw_line = lines[i]
        line = raw_line.strip()

        # Lewati baris kosong
        if not line:
            i += 1
            continue

        # 1. Separator line '---'
        if line == '---' or line == '***' or line == '___':
            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(4)
            p.paragraph_format.space_after = Pt(8)
            # garis pembatas tipis
            p_border = parse_xml(
                f'<w:pBdr {nsdecls("w")}><w:bottom w:val="single" w:sz="6" w:space="1" w:color="{HEX_GRAY_BORDER}"/></w:pBdr>'
            )
            p._p.get_or_add_pPr().append(p_border)
            i += 1
            continue

        # 2. Blok Kode / ASCII Diagram (``` ... ```)
        if line.startswith('```'):
            code_lines = []
            i += 1
            while i < n and not lines[i].strip().startswith('```'):
                code_lines.append(lines[i].rstrip('\r\n'))
                i += 1
            i += 1  # Lewati closing ```

            # Render sebagai callout box berlatar abu-abu dengan font Consolas
            tbl = doc.add_table(rows=1, cols=1)
            tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
            tbl.autofit = False
            cell = tbl.cell(0, 0)
            cell.width = Inches(6.5)
            set_cell_background(cell, HEX_GRAY_LIGHT)
            set_cell_margins(cell, top=100, bottom=100, left=150, right=150)
            
            # Border kotak tipis
            tcPr = cell._tc.get_or_add_tcPr()
            tcBrd = parse_xml(
                f'<w:tcBorders {nsdecls("w")}>'
                f'<w:top w:val="single" w:sz="4" w:space="0" w:color="{HEX_GRAY_BORDER}"/>'
                f'<w:left w:val="single" w:sz="4" w:space="0" w:color="{HEX_GRAY_BORDER}"/>'
                f'<w:bottom w:val="single" w:sz="4" w:space="0" w:color="{HEX_GRAY_BORDER}"/>'
                f'<w:right w:val="single" w:sz="4" w:space="0" w:color="{HEX_GRAY_BORDER}"/>'
                f'</w:tcBorders>'
            )
            tcPr.append(tcBrd)

            cell_p = cell.paragraphs[0]
            cell_p.paragraph_format.space_before = Pt(0)
            cell_p.paragraph_format.space_after = Pt(0)
            cell_p.paragraph_format.line_spacing = 1.0

            code_text = "\n".join(code_lines)
            run = cell_p.add_run(code_text)
            run.font.name = FONT_CODE
            run.font.size = Pt(8.5)
            run.font.color.rgb = COLOR_TEXT_MAIN

            # Spasi setelah kotak kode
            sp = doc.add_paragraph()
            sp.paragraph_format.space_before = Pt(0)
            sp.paragraph_format.space_after = Pt(4)
            continue

        # 3. Blockquotes / Alerts / Screenshot Slots (> ...)
        if line.startswith('>'):
            quote_lines = []
            while i < n and lines[i].strip().startswith('>'):
                cleaned_quote_line = lines[i].strip()[1:].strip()
                quote_lines.append(cleaned_quote_line)
                i += 1

            full_quote_text = " ".join(quote_lines)

            # Deteksi tipe alert / callout
            if "SLOT TANGKAPAN LAYAR" in full_quote_text:
                bg_hex = HEX_SLOT_BG
                border_hex = HEX_SLOT_BORDER
            elif "[!NOTE]" in full_quote_text:
                bg_hex = HEX_ALERT_NOTE_BG
                border_hex = HEX_ALERT_NOTE_BORDER
            elif "[!IMPORTANT]" in full_quote_text or "[!CAUTION]" in full_quote_text:
                bg_hex = HEX_ALERT_IMP_BG
                border_hex = HEX_ALERT_IMP_BORDER
            elif "[!WARNING]" in full_quote_text:
                bg_hex = HEX_ALERT_WARN_BG
                border_hex = HEX_ALERT_WARN_BORDER
            elif "[!TIP]" in full_quote_text:
                bg_hex = "ECFDF5"
                border_hex = "10B981"
            else:
                bg_hex = HEX_GRAY_LIGHT
                border_hex = "9CA3AF"

            cell = make_callout_box(doc, bg_hex, border_hex)

            for idx, ql in enumerate(quote_lines):
                if idx == 0:
                    qp = cell.paragraphs[0]
                else:
                    qp = cell.add_paragraph()

                qp.paragraph_format.space_before = Pt(1)
                qp.paragraph_format.space_after = Pt(2)
                qp.paragraph_format.line_spacing = 1.15

                # Bersihkan tag alert GitHub
                cleaned_text = re.sub(r'\[!(NOTE|IMPORTANT|WARNING|TIP|CAUTION)\]', r'**\1:**', ql)
                parse_inline_formatting(qp, cleaned_text, default_size=9.5, default_color=COLOR_TEXT_MAIN)

            # Spasi setelah box
            sp = doc.add_paragraph()
            sp.paragraph_format.space_before = Pt(0)
            sp.paragraph_format.space_after = Pt(4)
            continue

        # 4. Tabel Markdown (| ... |)
        if line.startswith('|') and '|' in line[1:]:
            table_lines = []
            while i < n and lines[i].strip().startswith('|'):
                table_lines.append(lines[i].strip())
                i += 1

            # Parsing baris tabel
            rows_data = []
            alignments = []
            for r_idx, t_line in enumerate(table_lines):
                # Buang pipa pembuka dan penutup
                content = t_line.strip()
                if content.startswith('|'):
                    content = content[1:]
                if content.endswith('|'):
                    content = content[:-1]

                cells = [c.strip() for c in content.split('|')]

                # Cek apakah ini baris pemisah separator (:---, :---:, ---:)
                if r_idx == 1 and all(re.match(r'^:?-+:?$', c) for c in cells):
                    for c in cells:
                        if c.startswith(':') and c.endswith(':'):
                            alignments.append(WD_ALIGN_PARAGRAPH.CENTER)
                        elif c.endswith(':'):
                            alignments.append(WD_ALIGN_PARAGRAPH.RIGHT)
                        else:
                            alignments.append(WD_ALIGN_PARAGRAPH.LEFT)
                    continue

                rows_data.append(cells)

            if rows_data:
                num_rows = len(rows_data)
                num_cols = max(len(r) for r in rows_data)

                # Samakan panjang kolom setiap baris
                for r in rows_data:
                    while len(r) < num_cols:
                        r.append("")

                table = doc.add_table(rows=num_rows, cols=num_cols)
                table.alignment = WD_TABLE_ALIGNMENT.CENTER
                table.autofit = True
                set_table_borders(table)

                # Format Header (Baris 0)
                for col_idx in range(num_cols):
                    cell = table.cell(0, col_idx)
                    set_cell_background(cell, HEX_PRIMARY)
                    set_cell_margins(cell, top=140, bottom=140, left=140, right=140)
                    p = cell.paragraphs[0]
                    p.paragraph_format.space_before = Pt(2)
                    p.paragraph_format.space_after = Pt(2)
                    p.alignment = alignments[col_idx] if col_idx < len(alignments) else WD_ALIGN_PARAGRAPH.LEFT
                    
                    header_text = rows_data[0][col_idx]
                    parse_inline_formatting(p, header_text, default_size=9.5, default_color=RGBColor(0xFF, 0xFF, 0xFF), default_font=FONT_HEADING)
                    for r in p.runs:
                        r.bold = True

                # Set tblHeader & cantSplit di XML agar rapi bila ganti halaman
                trPr = table.rows[0]._tr.get_or_add_trPr()
                trPr.append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))

                # Format Data Rows (Baris 1 s/d selesai)
                for row_idx in range(1, num_rows):
                    is_even = (row_idx % 2 == 0)
                    bg_row = HEX_GRAY_LIGHT if is_even else "FFFFFF"

                    row = table.rows[row_idx]
                    trPr = row._tr.get_or_add_trPr()
                    trPr.append(parse_xml(f'<w:cantSplit {nsdecls("w")}/>'))

                    for col_idx in range(num_cols):
                        cell = row.cells[col_idx]
                        set_cell_background(cell, bg_row)
                        set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
                        p = cell.paragraphs[0]
                        p.paragraph_format.space_before = Pt(1)
                        p.paragraph_format.space_after = Pt(1)
                        p.alignment = alignments[col_idx] if col_idx < len(alignments) else WD_ALIGN_PARAGRAPH.LEFT

                        cell_text = rows_data[row_idx][col_idx]
                        parse_inline_formatting(p, cell_text, default_size=9, default_color=COLOR_TEXT_MAIN)

                # Spasi setelah tabel
                sp = doc.add_paragraph()
                sp.paragraph_format.space_before = Pt(2)
                sp.paragraph_format.space_after = Pt(6)

            continue

        # 5. Heading 1 (# ...)
        if line.startswith('# '):
            text = line[2:].strip()
            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(16)
            p.paragraph_format.space_after = Pt(4)
            p.paragraph_format.keep_with_next = True
            parse_inline_formatting(p, text, default_size=18, default_color=COLOR_PRIMARY, default_font=FONT_HEADING)
            for r in p.runs:
                r.bold = True
            i += 1
            continue

        # 6. Heading 2 (## ...)
        if line.startswith('## '):
            text = line[3:].strip()
            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(14)
            p.paragraph_format.space_after = Pt(4)
            p.paragraph_format.keep_with_next = True
            parse_inline_formatting(p, text, default_size=14, default_color=COLOR_PRIMARY, default_font=FONT_HEADING)
            for r in p.runs:
                r.bold = True
            i += 1
            continue

        # 7. Heading 3 (### ...)
        if line.startswith('### '):
            text = line[4:].strip()
            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(10)
            p.paragraph_format.space_after = Pt(3)
            p.paragraph_format.keep_with_next = True
            parse_inline_formatting(p, text, default_size=12, default_color=COLOR_SECONDARY, default_font=FONT_HEADING)
            for r in p.runs:
                r.bold = True
            i += 1
            continue

        # 8. Heading 4 (#### ...)
        if line.startswith('#### '):
            text = line[5:].strip()
            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(8)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.keep_with_next = True
            parse_inline_formatting(p, text, default_size=11, default_color=COLOR_TEXT_MAIN, default_font=FONT_HEADING)
            for r in p.runs:
                r.bold = True
            i += 1
            continue

        # 9. List Items (Bulleted / Numbered)
        bullet_m = re.match(r'^(\s*)([-*]|\d+\.)\s+(.+)$', raw_line)
        if bullet_m:
            indent_spaces = len(bullet_m.group(1))
            bullet_char = bullet_m.group(2)
            item_text = bullet_m.group(3)

            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(1)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15

            # Indentasi hierarkis
            base_indent = 0.25
            if indent_spaces >= 6:
                p.paragraph_format.left_indent = Inches(base_indent * 3)
            elif indent_spaces >= 2:
                p.paragraph_format.left_indent = Inches(base_indent * 2)
            else:
                p.paragraph_format.left_indent = Inches(base_indent)

            # Tambahkan simbol penanda bullet/number
            if bullet_char.endswith('.'):
                marker = bullet_char + " "
            elif indent_spaces >= 2:
                marker = "○  "
            else:
                marker = "•  "

            run_marker = p.add_run(marker)
            run_marker.font.name = FONT_MAIN
            run_marker.font.size = Pt(10)
            run_marker.font.color.rgb = COLOR_SECONDARY
            run_marker.bold = True

            parse_inline_formatting(p, item_text, default_size=10, default_color=COLOR_TEXT_MAIN)
            i += 1
            continue

        # 10. Normal Paragraph
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(2)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.line_spacing = 1.15
        parse_inline_formatting(p, line, default_size=10, default_color=COLOR_TEXT_MAIN)
        i += 1

    # Simpan Berkas DOCX
    doc.save(docx_path)
    print(f" -> Berhasil disimpan: {docx_path.name}")

def main():
    docs_dir = Path(r"c:\laragon\www\sim-libas\docs\panduan")
    md_files = sorted(docs_dir.glob("*.md"))
    
    if not md_files:
        print("Tidak ada berkas markdown ditemukan di docs/panduan/")
        return

    print(f"Ditemukan {len(md_files)} berkas panduan markdown untuk dikonversi ke .docx:\n")
    
    success_count = 0
    for md_file in md_files:
        docx_file = md_file.with_suffix('.docx')
        try:
            convert_md_file_to_docx(md_file, docx_file)
            success_count += 1
        except Exception as e:
            print(f" [GAGAL] Gagal mengonversi {md_file.name}: {e}")
            import traceback
            traceback.print_exc()

    print(f"\nSelesai! Berhasil mengonversi {success_count} dari {len(md_files)} berkas panduan ke format .docx.")

if __name__ == "__main__":
    main()
