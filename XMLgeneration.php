// XML generation
				
				$xml=new DomDocument('1.0', 'UTF-8');
        		$xml->formatOutput=true;
        		
        		$xml->save($filexml);
        		
        		$invoice=$xml->createElement("Invoice");
        			
        		$invoice->setAttribute("xmlns","urn:oasis:names:specification:ubl:schema:xsd:Invoice-2");
        		$invoice->setAttribute("xmlns:cbc","urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
        		$invoice->setAttribute("xmlns:cac","urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
        		$invoice->setAttribute("xmlns:ns4","urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
        		$invoice->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
        		$invoice->setAttribute("xsi:schemaLocation","urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd");
        				
        		$xml->appendChild($invoice);
        				
        		$CustomizationID=$xml->createElement("cbc:CustomizationID","urn:cen.eu:en16931:2017#compliant#urn:efactura.mfinante.ro:CIUS-RO:1.0.1");
        		$invoice->appendChild($CustomizationID);
        		$xml->save($filexml);
				
				
				$this->_createxml($filexml, 1);
				
//XML account details
				        $currency_code=$object->multicurrency_code;
				        
				        $this->ht_total =round((($conf->multicurrency->enabled && isset($object->multicurrency_tx) && $object->multicurrency_tx != 1) ? $object->multicurrency_total_ht : $object->total_ht), 2);
		                $this->ttc_total =round((($conf->multicurrency->enabled && $object->multicurrency_tx != 1) ? $object->multicurrency_total_ttc : $object->total_ttc), 2);
				        
				            $cbc_ref_ID=$xml->createElement("cbc:ID", $this->ref_ID);
					        $invoice->appendChild($cbc_ref_ID);
					        
					        $cbc_issue_date=$xml->createElement("cbc:IssueDate", $this->issuedate);
					        $invoice->appendChild($cbc_issue_date);
					        
					        $cbc_due_date=$xml->createElement("cbc:DueDate", $this->duedate);
					        $invoice->appendChild($cbc_due_date);
					        
					        $cbc_InvoiceTypeCode=$xml->createElement("cbc:InvoiceTypeCode", 380);
					        $invoice->appendChild($cbc_InvoiceTypeCode);
					        
					        $cbc_TaxPointDate=$xml->createElement("cbc:TaxPointDate", $this->pointoftaxdate);
					        if (!$this->pointoftaxdate=="")  $invoice->appendChild($cbc_TaxPointDate);
					        
					        
					        $this->currcode= !empty($currency) ? $currency : $conf->currency;
					        $cbc_currency_code=$xml->createElement("cbc:DocumentCurrencyCode", $this->currcode);
					        $invoice->appendChild($cbc_currency_code);
					        
					        $cbc_order_reference=$xml->createElement("cac:OrderReference");
					        $cbc_order_reference=$xml->createElement("cac:OrderReference");
					        if (!($this->refClient=="")) {
					        $invoice->appendChild($cbc_order_reference);
					        
					            $cbc_due_date_ID=$xml->createElement("cbc:ID", $this->refClient);
					            $cbc_order_reference->appendChild($cbc_due_date_ID);}
					            
					            
                            //Supplier Party
                            
                            
					            
			                $cac_AccountingSupplierParty=$xml->createElement("cac:AccountingSupplierParty");
					        $invoice->appendChild($cac_AccountingSupplierParty);
					        
					        $supplier_address=$outputlangs->convToOutputCharset($this->emetteur->address);
					        $supplier_town=$outputlangs->convToOutputCharset($this->emetteur->town);
					        $supplier_zip=$outputlangs->convToOutputCharset($this->emetteur->zip);
					        $supplier_country_code=$outputlangs->convToOutputCharset($this->emetteur->country_code);
					        $supplier_state=$outputlangs->convToOutputCharset($supplier_country_code."-".$this->emetteur->state_code);
					        
					        
					        
					            $Supplier_party=$xml->createElement("cac:Party");
					            $cac_AccountingSupplierParty->appendChild($Supplier_party);
					            
					                $Supplier_postal_address=$xml->createElement("cac:PostalAddress");
					                $Supplier_party->appendChild($Supplier_postal_address);
					                
					                    
					                    $Supplier_party_street=$xml->createElement("cbc:StreetName", $supplier_address);
					                    $Supplier_postal_address->appendChild($Supplier_party_street);
					                    
					                    
					                    $Supplier_CityName=$xml->createElement("cbc:CityName", $supplier_town);
					                    $Supplier_postal_address->appendChild($Supplier_CityName);
					                    
					                    
					                    $Supplier_PostalZone=$xml->createElement("cbc:PostalZone", $supplier_zip);
					                    $Supplier_postal_address->appendChild($Supplier_PostalZone);
					                    
					                    
					                    $Supplier_CountrySubentity=$xml->createElement("cbc:CountrySubentity", $supplier_state);
					                    $Supplier_postal_address->appendChild($Supplier_CountrySubentity);
					                    
					                    $Supplier_Country=$xml->createElement("cac:Country");
					                    $Supplier_postal_address->appendChild($Supplier_Country);
					                    
					                            
					                            $Supplier_IdentificationCode=$xml->createElement("cbc:IdentificationCode", $supplier_country_code);
					                            $Supplier_Country->appendChild($Supplier_IdentificationCode);
					                            
					                $Supplier_PartyTaxScheme=$xml->createElement("cac:PartyTaxScheme");
					                $Supplier_party->appendChild($Supplier_PartyTaxScheme);
					                
					                    $Supplier_CompanyID=$xml->createElement("cbc:CompanyID", $this->supplier_cif);
					                    $Supplier_PartyTaxScheme->appendChild($Supplier_CompanyID);
					                    
					                        $Supplier_company_TaxScheme=$xml->createElement("cac:TaxScheme");
					                        $Supplier_PartyTaxScheme->appendChild($Supplier_company_TaxScheme);
					                        
					                            $Supplier_company_TaxScheme_ID=$xml->createElement("cbc:ID", "VAT");
					                            $Supplier_company_TaxScheme->appendChild($Supplier_company_TaxScheme_ID);
					                            
					                $Supplier_PartyLegalEntity=$xml->createElement("cac:PartyLegalEntity");
					                $Supplier_party->appendChild($Supplier_PartyLegalEntity);
					                
					                //$supplier_name = pdf_getSubstitutionArray(__MYCOMPANY_NAME__);
					                $supplier_name=$outputlangs->convToOutputCharset($this->emetteur->name);
					                
					                  //  $Supplier_RegistrationName=$xml->createElement("cbc:RegistrationName", $supplier_name);
					                    $Supplier_RegistrationName=$xml->createElement("cbc:RegistrationName", $supplier_name);
					                    $Supplier_PartyLegalEntity->appendChild($Supplier_RegistrationName);
					                    
					                    $Supplier_CompanyID=$xml->createElement("cbc:CompanyID", $this->supplier_cif);
					                    $Supplier_PartyLegalEntity->appendChild($Supplier_CompanyID);
					                    
                                    $Supplier_PartyContact=$xml->createElement("cac:Contact");
					                $Supplier_party->appendChild($Supplier_PartyContact);
					                
					                    $Supplier_ContactName=$xml->createElement("cbc:Name", $this->supplier_name);
					                    if (!($this->supplier_name=="")) $Supplier_PartyContact->appendChild($Supplier_ContactName);
					                    //$Supplier_PartyContact->appendChild($Supplier_ContactName);
					                    
					                    $Supplier_ContactTelephone=$xml->createElement("cbc:Telephone", $this->supplier_tel);
					                    $Supplier_PartyContact->appendChild($Supplier_ContactTelephone);
					                    
					                    $Supplier_ContactElectronicMail=$xml->createElement("cbc:ElectronicMail", $this->supplier_email);
					                    $Supplier_PartyContact->appendChild($Supplier_ContactElectronicMail);
					        
					        
					        //Customer Party
					        
					        
					        $thirdparty_address=$outputlangs->convToOutputCharset($object->thirdparty->address);
					        $thirdparty_town=$outputlangs->convToOutputCharset($object->thirdparty->town);
					        $thirdparty_zip=$outputlangs->convToOutputCharset($object->thirdparty->zip);
					        $thirdparty_country_code=$outputlangs->convToOutputCharset($object->thirdparty->country_code);
					        $thirdparty_state=$outputlangs->convToOutputCharset($thirdparty_country_code."-".$object->thirdparty->state_code);
					        //$billing_contact= $outputlangs->convToOutputCharset($targetcontact->getFullName($outputlangs, 1));
					            
			                $cac_AccountingCustomerParty=$xml->createElement("cac:AccountingCustomerParty");
					        $invoice->appendChild($cac_AccountingCustomerParty);
					        
					            $Customer_party=$xml->createElement("cac:Party");
					            $cac_AccountingCustomerParty->appendChild($Customer_party);
					            
					                $Customer_postal_address=$xml->createElement("cac:PostalAddress");
					                $Customer_party->appendChild($Customer_postal_address);
					                
					                    $Customer_party_street=$xml->createElement("cbc:StreetName", $thirdparty_address);
					                    $Customer_postal_address->appendChild($Customer_party_street);
					                    
					                    $Customer_CityName=$xml->createElement("cbc:CityName", $thirdparty_town);
					                    $Customer_postal_address->appendChild($Customer_CityName);
					                    
					                    $Customer_PostalZone=$xml->createElement("cbc:PostalZone", $thirdparty_zip);
					                    $Customer_postal_address->appendChild($Customer_PostalZone);
					                    
					                    $Customer_CountrySubentity=$xml->createElement("cbc:CountrySubentity", $thirdparty_state);
					                    $Customer_postal_address->appendChild($Customer_CountrySubentity);
					                    
					                    $Customer_Country=$xml->createElement("cac:Country");
					                    $Customer_postal_address->appendChild($Customer_Country);
					                    
					                            $Customer_IdentificationCode=$xml->createElement("cbc:IdentificationCode", $thirdparty_country_code);
					                            $Customer_Country->appendChild($Customer_IdentificationCode);
					                            
					                $Customer_PartyTaxScheme=$xml->createElement("cac:PartyTaxScheme");
					                $Customer_party->appendChild($Customer_PartyTaxScheme);
					                
					                    if (!(substr($this->Customer_cif, 0, 2)==$thirdparty_country_code)) $this->Customer_cif_mod = $thirdparty_country_code.$this->Customer_cif;
					                    $Customer_CompanyID=$xml->createElement("cbc:CompanyID", $this->Customer_cif_mod);
					                    $Customer_PartyTaxScheme->appendChild($Customer_CompanyID);
					                    
					                        $Customer_company_TaxScheme=$xml->createElement("cac:TaxScheme");
					                        $Customer_PartyTaxScheme->appendChild($Customer_company_TaxScheme);
					                        
					                            $Customer_company_TaxScheme_ID=$xml->createElement("cbc:ID", "VAT");
					                            $Customer_company_TaxScheme->appendChild($Customer_company_TaxScheme_ID);
					                            
					                $Customer_PartyLegalEntity=$xml->createElement("cac:PartyLegalEntity");
					                $Customer_party->appendChild($Customer_PartyLegalEntity);
					                
					                    $Customer_RegistrationName=$xml->createElement("cbc:RegistrationName", $this->Customername);
					                    $Customer_PartyLegalEntity->appendChild($Customer_RegistrationName);
					                    
					                    //if (!(substr($this->Customer_cif, 0, 2)==$thirdparty_country_code)) $this->Customer_cif = $thirdparty_country_code.$this->Customer_cif;
					                    
					                    $Customer_CompanyID=$xml->createElement("cbc:CompanyID", $this->Customer_cif);
					                    $Customer_PartyLegalEntity->appendChild($Customer_CompanyID);
					                    
                                    $Customer_PartyContact=$xml->createElement("cac:Contact");
					                $Customer_party->appendChild($Customer_PartyContact);
					                
					                    $Customer_ContactName=$xml->createElement("cbc:Name", $this->billing_contact);
					                    if (!($this->billing_contact=="")) $Customer_PartyContact->appendChild($Customer_ContactName);
					                    
					                    $Customer_ContactTelephone=$xml->createElement("cbc:Telephone", $this->customer_tel);
					                    $Customer_PartyContact->appendChild($Customer_ContactTelephone);
					                    
					                    $Customer_ContactElectronicMail=$xml->createElement("cbc:ElectronicMail", $this->customer_email);
					                    $Customer_PartyContact->appendChild($Customer_ContactElectronicMail);
					        
					        //Delivery Location
					        
					        $Delivery=$xml->createElement("cac:Delivery");
					        $invoice->appendChild($Delivery);
					        
					            $ActualDeliveryDate=$xml->createElement("cbc:ActualDeliveryDate");
					            //$Delivery->appendChild($ActualDeliveryDate);
					            
					            $DeliveryLocation=$xml->createElement("cac:DeliveryLocation");
					            $Delivery->appendChild($DeliveryLocation);
					            
					                $DeliveryAddress=$xml->createElement("cac:Address");
					                $DeliveryLocation->appendChild($DeliveryAddress);
					            
					                    $Delivery_StreetName=$xml->createElement("cbc:StreetName", $thirdparty_address);
					                    $DeliveryAddress->appendChild($Delivery_StreetName);
					                
					                    $Delivery_CityName=$xml->createElement("cbc:CityName", $thirdparty_town);
					                    $DeliveryAddress->appendChild($Delivery_CityName);
					                    
					                    $Delivery_PostalZone=$xml->createElement("cbc:PostalZone", $thirdparty_zip);
					                    $DeliveryAddress->appendChild($Delivery_PostalZone);
					                    
					                    $Delivery_CountrySubentity=$xml->createElement("cbc:CountrySubentity", $thirdparty_state);
					                    $DeliveryAddress->appendChild($Delivery_CountrySubentity);
					                    
					                    $Delivery_Country=$xml->createElement("cac:Country");
					                    $DeliveryAddress->appendChild($Delivery_Country);
					                    
					                           
					                            $Delivery_CountryIdentificationCode=$xml->createElement("cbc:IdentificationCode", $thirdparty_country_code);
					                            $Delivery_Country->appendChild($Delivery_CountryIdentificationCode);
					        
					        
					        
					        //Payment means
					        
							$PaymentMeans=$xml->createElement("cac:PaymentMeans");
							$invoice->appendChild($PaymentMeans);
							
							
							
							//cbc:TaxAmount
							
							$TaxTotal=$xml->createElement("cac:TaxTotal");
					        $invoice->appendChild($TaxTotal);
					               
					                                
		                    //cac:LegalMonetaryTotal
		                    
		                    
		                    $LegalMonetaryTotal=$xml->createElement("cac:LegalMonetaryTotal");
					        $invoice->appendChild($LegalMonetaryTotal);
					                //Tax Subtotal
					                $LineExtensionAmount=$xml->createElement("cbc:LineExtensionAmount", $this->ht_total);
					                $LineExtensionAmount->setAttribute("currencyID", $currency_code);
					                $LegalMonetaryTotal->appendChild($LineExtensionAmount);
					                
					                $TaxExclusiveAmount=$xml->createElement("cbc:TaxExclusiveAmount", $this->ht_total);
					                $TaxExclusiveAmount->setAttribute("currencyID", $currency_code);
					                $LegalMonetaryTotal->appendChild($TaxExclusiveAmount);     
					                
					                $TaxInclusiveAmount=$xml->createElement("cbc:TaxInclusiveAmount", $this->ttc_total);
					                $TaxInclusiveAmount->setAttribute("currencyID", $currency_code);
					                $LegalMonetaryTotal->appendChild($TaxInclusiveAmount);     
					                
					                $PayableAmount=$xml->createElement("cbc:PayableAmount", $this->ttc_total);
					                $PayableAmount->setAttribute("currencyID", $currency_code);
					                $LegalMonetaryTotal->appendChild($PayableAmount);     
					           
							
							//Save XML
							$xml->save($this->filexml);
							
////XML XML Invoice Line  Se pune in Loop();
					
					$invoiceline=$xml->createElement("cac:InvoiceLine");
					$invoice->appendChild($invoiceline);
					
					$ID=$xml->createElement("cbc:ID", $i+1);
					$invoiceline->appendChild($ID);
					
					$InvoicedQuantity=$xml->createElement("cbc:InvoicedQuantity", $qty);
					
					if ($unit=="m") $unitxml="MTR";
					if ($unit=="buc") $unitxml="H87";
					
					$InvoicedQuantity->setAttribute("unitCode",$unitxml);
					$invoiceline->appendChild($InvoicedQuantity);
					
					
					$total_excl_tax_xml=str_replace(' ', '', $total_excl_tax);
					
					
					
					$LineExtensionAmount=$xml->createElement("cbc:LineExtensionAmount", str_replace(",",".",$total_excl_tax_xml));
					$LineExtensionAmount->setAttribute("currencyID", $currency_code);
					$invoiceline->appendChild($LineExtensionAmount);
					
					$item=$xml->createElement("cac:Item");
					$invoiceline->appendChild($item);
						
						
						$this->part_description=pdf_getlinedesc($object, $i, $outputlangs, $hideref, $hidedesc, $issupplierline);
						$this->part_description=strip_tags($this->part_description);
						$Description=$xml->createElement("cbc:Description", $this->part_description);
						$item->appendChild($Description);
						
						$productref= pdf_getlineref($object, $i, $outputlangs, $hidedetails = 0);
						
						if ($productref=="") $productref=$this->part_description;
						
						$Name=$xml->createElement("cbc:Name", $productref);
						$item->appendChild($Name);
						
						$ClassifiedTaxCategory=$xml->createElement("cac:ClassifiedTaxCategory");
						$item->appendChild($ClassifiedTaxCategory);
						
							$cbcID=$xml->createElement("cbc:ID", "S");
							$ClassifiedTaxCategory->appendChild($cbcID);
							
							$cbcPercent=$xml->createElement("cbc:Percent", str_replace("%","",$vat_rate));
							$ClassifiedTaxCategory->appendChild($cbcPercent);
							
							$TaxScheme=$xml->createElement("cac:TaxScheme");
							$ClassifiedTaxCategory->appendChild($TaxScheme);
							
								$cbcID=$xml->createElement("cbc:ID", "VAT");
								$TaxScheme->appendChild($cbcID);
								
					$Price=$xml->createElement("cac:Price");
					$invoiceline->appendChild($Price);
						
							$cbcPriceAmount=$xml->createElement("cbc:PriceAmount", $up_net);
							$cbcPriceAmount->setAttribute("currencyID", $currency_code);
							$Price->appendChild($cbcPriceAmount);
					
					$xml->save($this->filexml);
					

				//Payment Means Detail
        							$PaymentMeansCode=$xml->createElement("cbc:PaymentMeansCode", 42);
        							$PaymentMeans->appendChild($PaymentMeansCode);
        							$PayeeFinancialAccount=$xml->createElement("cac:PayeeFinancialAccount");
        							$PaymentMeans->appendChild($PayeeFinancialAccount);
        							$PaymentMeansID=$xml->createElement("cbc:ID", $this->account);
        							$PayeeFinancialAccount->appendChild($PaymentMeansID);
				 //Tax Subtotal
					                $TaxAmount=$xml->createElement("cbc:TaxAmount", $this->tva_total);
					                $TaxAmount->setAttribute("currencyID", $currency_code);
					                $TaxTotal->appendChild($TaxAmount);
					                
					                $TaxSubtotal=$xml->createElement("cac:TaxSubtotal");
					                $TaxTotal->appendChild($TaxSubtotal);
					                
					                        //Tax Subtotal
					                        $TaxableAmount=$xml->createElement("cbc:TaxableAmount", $this->ht_total);
					                        $TaxableAmount->setAttribute("currencyID", $currency_code);
					                        $TaxSubtotal->appendChild($TaxableAmount);
					                        $TaxSubtotalTaxAmount=$xml->createElement("cbc:TaxAmount", $this->tva_total);
					                        $TaxSubtotalTaxAmount->setAttribute("currencyID", $currency_code);
					                        $TaxSubtotal->appendChild($TaxSubtotalTaxAmount);
					                        $TaxSubtotalTaxAmountTaxCategory=$xml->createElement("cac:TaxCategory");
					                        $TaxSubtotal->appendChild($TaxSubtotalTaxAmountTaxCategory);
					                      
					                                //Tax category
					                                $TaxSubtotalID=$xml->createElement("cbc:ID", "S");
					                                $TaxSubtotalTaxAmountTaxCategory->appendChild($TaxSubtotalID);
					                                $TaxSubtotalPercent=$xml->createElement("cbc:Percent", str_replace("%","",$vat_rate));
					                                $TaxSubtotalTaxAmountTaxCategory->appendChild($TaxSubtotalPercent);
					                                $TaxSubtotalTaxScheme=$xml->createElement("cac:TaxScheme");
					                                $TaxSubtotalTaxAmountTaxCategory->appendChild($TaxSubtotalTaxScheme);
					                                        $TaxSubtotalTaxSchemeID=$xml->createElement("cbc:ID", "VAT");
					                                        $TaxSubtotalTaxScheme->appendChild($TaxSubtotalTaxSchemeID);
					                                        
					           //Save XML
							$xml->save($this->filexml);
							
