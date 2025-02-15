from docx.oxml import parse_xml
from classes.docx.CT_Anchor import CT_Anchor


class CT_Line(CT_Anchor):
    @classmethod
    def new(cls, cx, cy, shape_id, pos_x, pos_y, color='FFFFFF'):
        anchor = parse_xml(CT_Anchor._anchor_xml(pos_x, pos_y, None))
        anchor.extent.cx = cx
        anchor.extent.cy = cy
        anchor.docPr.id = shape_id
        anchor.docPr.name = f'Line {shape_id}'
        anchor.graphic.graphicData.uri = ('http://schemas.microsoft.com/office/word/2010/wordprocessingShape')
        wsp = parse_xml(cls.wsp_xml(cx, cy, color))
        anchor.graphic.graphicData.append(wsp)
        return anchor

    @classmethod
    def wsp_xml(cls, cx, cy, color='FFFFFF'):
        if color is None:
            color = 'FFFFFF'
        return (
            f"""<wps:wsp xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"
                        xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                <wps:cNvCnPr/>
                <wps:spPr>
                    <a:xfrm flipV="1">
                        <a:off x="0" y="0"/>
                        <a:ext cx="{cx}" cy="{cy}"/>
                    </a:xfrm>
                    <a:prstGeom prst="line">
                        <a:avLst/>
                    </a:prstGeom>
                    <a:ln w="{cy}">
                        <a:solidFill>
                            <a:srgbClr val="{color}"/>
                        </a:solidFill>
                    </a:ln>
                </wps:spPr>
                <wps:style>
                    <a:lnRef idx="1">
                        <a:schemeClr val="accent2"/>
                    </a:lnRef>
                    <a:fillRef idx="0">
                        <a:schemeClr val="accent2"/>
                    </a:fillRef>
                    <a:effectRef idx="0">
                        <a:schemeClr val="accent2"/>
                    </a:effectRef>
                    <a:fontRef idx="minor">
                        <a:schemeClr val="tx1"/>
                    </a:fontRef>
                </wps:style>
                <wps:bodyPr/>
            </wps:wsp>"""
        )
