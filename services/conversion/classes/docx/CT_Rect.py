from docx.oxml import parse_xml
from classes.docx.CT_Anchor import CT_Anchor


class CT_Rect(CT_Anchor):
    #choice = OneAndOnlyOne('mc:Choice')
    #fallback = OneAndOnlyOne('mc:Fallback')

    @classmethod
    def new(cls, cx, cy, shape_id, pos_x, pos_y, fill='FFFFFF', stroke='FFFFFF', is_set_text_box=False, valign="t"):
        anchor = parse_xml(CT_Anchor._anchor_xml(pos_x, pos_y, None))
        anchor.extent.cx = cx
        anchor.extent.cy = cy
        anchor.docPr.id = shape_id
        anchor.docPr.name = f'Rect {shape_id}'
        anchor.graphic.graphicData.uri = ('http://schemas.microsoft.com/office/word/2010/wordprocessingShape')
        wsp = parse_xml(cls.wsp_xml(cx, cy, fill, stroke, is_set_text_box))
        text_box = None

        if is_set_text_box:
            namespaces = {
                'a': "http://schemas.openxmlformats.org/drawingml/2006/main",
                'wps': "http://schemas.microsoft.com/office/word/2010/wordprocessingShape",
                'w': "http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            }

            txbx = wsp.find('wps:txbx', namespaces)
            if txbx is not None:
                text_box = txbx.find('w:txbxContent', namespaces)

        anchor.graphic.graphicData.append(wsp)
        return anchor, text_box

    @classmethod
    def wsp_xml(cls, cx, cy, fill='FFFFFF', stroke='FFFFFF', is_set_text_box=False, valign="t"):
        print(fill)
        if fill is None:
            fill = 'FFFFFF'
        if stroke is None:
            stroke = 'FFFFFF'

        text_box = ''
        if is_set_text_box:
            text_box = cls.text_box_xml()
        return (
            f"""<wps:wsp xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"
                        xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                <wps:cNvSpPr/>
                <wps:spPr>
                    <a:xfrm>
                        <a:off x="0" y="0"/>
                        <a:ext cx="{cx}" cy="{cy}"/>
                    </a:xfrm>
                    <a:prstGeom prst="rect">
                        <a:avLst/>
                    </a:prstGeom>
                    <a:solidFill>
                        <a:srgbClr val="{fill}"/>
                    </a:solidFill>
                    <a:ln>
                        <a:solidFill>
                            <a:srgbClr val="{stroke}"/>
                        </a:solidFill>
                    </a:ln>
                </wps:spPr>
                <wps:style>
                    <a:lnRef idx="2">
                        <a:schemeClr val="accent6"/>
                    </a:lnRef>
                    <a:fillRef idx="1">
                        <a:schemeClr val="lt1"/>
                    </a:fillRef>
                    <a:effectRef idx="0">
                        <a:schemeClr val="accent6"/>
                    </a:effectRef>
                    <a:fontRef idx="minor">
                        <a:schemeClr val="dk1"/>
                    </a:fontRef>
                </wps:style>
                {text_box}
                <wps:bodyPr rot="0" spcFirstLastPara="0" vertOverflow="overflow"
                            horzOverflow="overflow" vert="horz" wrap="square" lIns="0"
                            tIns="0" rIns="0" bIns="0" numCol="1" spcCol="0"
                            anchor="{valign}" anchorCtr="0">
                    <a:prstTxWarp prst="textNoShape">
                        <a:avLst/>
                    </a:prstTxWarp>
                    <a:noAutofit/>
                </wps:bodyPr>
            </wps:wsp>"""
        )

    @classmethod
    def text_box_xml(cls):
        return (
            """<wps:txbx xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"
                         xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
                <w:txbxContent>
                </w:txbxContent>
            </wps:txbx>"""
        )
