"""
 Copyright 2024 Adobe
 All Rights Reserved.

 NOTICE: Adobe permits you to use, modify, and distribute this file in
 accordance with the terms of the Adobe license agreement accompanying it.
"""

import logging
import os
from datetime import datetime

from adobe.pdfservices.operation.auth.service_principal_credentials import ServicePrincipalCredentials
from adobe.pdfservices.operation.exception.exceptions import ServiceApiException, ServiceUsageException, SdkException
from adobe.pdfservices.operation.io.cloud_asset import CloudAsset
from adobe.pdfservices.operation.io.stream_asset import StreamAsset
from adobe.pdfservices.operation.pdf_services import PDFServices
from adobe.pdfservices.operation.pdf_services_media_type import PDFServicesMediaType
from adobe.pdfservices.operation.pdfjobs.jobs.export_pdf_job import ExportPDFJob
from adobe.pdfservices.operation.pdfjobs.params.export_pdf.export_pdf_params import ExportPDFParams
from adobe.pdfservices.operation.pdfjobs.params.export_pdf.export_pdf_target_format import ExportPDFTargetFormat
from adobe.pdfservices.operation.pdfjobs.result.export_pdf_result import ExportPDFResult

# Initialize the logger
logging.basicConfig(level=logging.INFO)


#
# This sample illustrates how to export a PDF file to a Word (DOCX) file
#
# Refer to README.md for instructions on how to run the samples.
#
def adobe_pdfservices(file_path: str, output_path: str, target_format='docx'):
    try:
        file = open(file_path, 'rb')
        input_stream = file.read()
        file.close()

        # Initial setup, create credentials instance
        credentials = ServicePrincipalCredentials(
            client_id='dc05f6a1bcaa40988c8422254c457e06',
            client_secret='p8e-uEmZqCF_nP0qva3rJfFrp2m2YLtyS83W'
        )

        if target_format == 'docx':
            target_format = ExportPDFTargetFormat.DOCX
        elif target_format == 'doc':
            target_format = ExportPDFTargetFormat.DOC
        elif target_format == 'pptx':
            target_format = ExportPDFTargetFormat.PPTX
        elif target_format == 'xlsx':
            target_format = ExportPDFTargetFormat.XLSX
        elif target_format == 'rtf':
            target_format = ExportPDFTargetFormat.RTF
        else:
            raise RuntimeError("Unsupported target format: {}".format(target_format))

        # Creates a PDF Services instance
        pdf_services = PDFServices(credentials=credentials)

        # Creates an asset(s) from source file(s) and upload
        input_asset = pdf_services.upload(input_stream=input_stream, mime_type=PDFServicesMediaType.PDF)

        # Create parameters for the job
        export_pdf_params = ExportPDFParams(target_format=target_format)

        # Creates a new job instance
        export_pdf_job = ExportPDFJob(input_asset=input_asset, export_pdf_params=export_pdf_params)

        # Submit the job and gets the job result
        count = 0
        location = pdf_services.submit(export_pdf_job)
        pdf_services_response = None
        while count < 3:
            try:
                pdf_services_response = pdf_services.get_job_result(location, ExportPDFResult)
                break
            except ServiceApiException as e:
                print(e)
                count += 1

        if not pdf_services_response:
            raise Exception('Failed to upload PDF job.')

        # Get content from the resulting asset(s)
        result_asset: CloudAsset = pdf_services_response.get_result().get_asset()
        stream_asset: StreamAsset = pdf_services.get_content(result_asset)

        with open(output_path, "wb") as file:
            file.write(stream_asset.get_input_stream())

        return output_path

    except (ServiceApiException, ServiceUsageException, SdkException) as e:
        logging.exception(f'Exception encountered while executing operation: {e}')
        return file_path


