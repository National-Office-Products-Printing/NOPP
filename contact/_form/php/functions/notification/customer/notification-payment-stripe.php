<?php
					
	if($sendcopytome == true){  
										
		if($finalsendtome === $lang['form-send-to-me-option-1']){
			
			if($automessage == true){
	
				if ($mysql == true){
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													db_insert_payment($tablenamepayment,$insertpaymentvalues);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {														
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													db_insert_payment($tablenamepayment,$insertpaymentvalues);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														db_insert_payment($tablenamepayment,$insertpaymentvalues);	
														redirecttime($paymentsuccess1);
													}
												} else {														
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {														
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										
										include 'messages/customer/message-copy-payment.php';
										include 'emailcopy.php';
										
										if($emailcopy->send()){
											if($sendmobile == true){
												if($redirect == true){
													if($multilanguage == true) {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														$mobile->send($notification, $phone, $unicode);
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-success-payment-message'];
												}
											} else {
												if($redirect == true){
													if($multilanguage == true) {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess2);
													} else {
														echo $lang['form-wait-message'];
														redirecttime($paymentsuccess1);
													}
												} else {														
													echo $lang['form-success-payment-message'];
												}
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror2);																
												} else {
													echo $lang['form-wait-message'];
													redirecttime($emailcopyerror1);
												}
											} else {
												echo $lang['form-email-copy-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
				
			} else {
				
				if ($mysql == true){
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
																																							
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/message-copy-payment.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($emailcopyerror1);
											}
										} else {
											echo $lang['form-email-copy-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
			}
			
		} else {
			
			if($automessage == true){
	
				if ($mysql == true){
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_payment($tablenamepayment,$insertpaymentvalues);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_payment($tablenamepayment,$insertpaymentvalues);	
													redirecttime($paymentsuccess1);
												}
											} else {														
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
								
									include 'messages/customer/automessage-payment.php';
									include 'automessage.php';
								
									if($automail->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-payment-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($paymentsuccess1);
												}
											} else {														
												echo $lang['form-success-payment-message'];
											}
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($automailerror2);																
											} else {
												echo $lang['form-wait-message'];
												redirecttime($automailerror1);
											}
										} else {
											echo $lang['form-automessage-message'];
										}
									}	
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
				
			} else {
				
				if ($mysql == true){
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
					
				} else {
					
					if($signature == true){
										
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
								
								include 'functions/signature/signature.php';
								
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
						
					} else {
						
						$finalstripetoken = \Stripe\Token::create(array(
							'card' => array(
								'name' => $finalstripecardname,
								'number' => $finalstripecardnumber,
								'exp_month' => $finalstripecardmonth,
								'exp_year' => $finalstripecardyear,
								'cvc' => $finalstripecardcvc,
							)
						));
					
						$stripecustomer = \Stripe\Customer::create(array(
							'id' => $finalcustomerid,
							'email'  => $finalemail,
							'source'   => $finalstripetoken,
							'description' => $onetimestripedesc
						));
						
						if($stripecustomer){
							
							$stripecharge = \Stripe\Charge::create(array(
								'customer' => $finalcustomerid,
								'currency' => $paymentcurrency,
								'amount' => $finalstripeprice,
								'description' => $onetimestripedesc,
								'statement_descriptor' => $company
							));
							
							if($stripecharge){
																																									
								if($adminemail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($mailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($mailerror1);
										}
									} else {
										echo $lang['form-mail-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($transactionerror1);
									}
								} else {
									echo $lang['form-transaction-message'];
								}										
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($customererror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($customererror1);
								}
							} else {
								echo $lang['form-customer-message'];
							}										
						}
					}
				}
			}
		}
		
	} else {
		
		if($automessage == true){
	
			if ($mysql == true){
				
				if($signature == true){
									
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
																																								
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_payment($tablenamepayment,$insertpaymentvalues);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_payment($tablenamepayment,$insertpaymentvalues);	
												redirecttime($paymentsuccess1);
											}
										} else {														
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
				
			} else {
				
				if($signature == true){
									
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
																																								
							if($adminemail->send()){
							
								include 'messages/customer/automessage-payment.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-payment-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($paymentsuccess1);
											}
										} else {														
											echo $lang['form-success-payment-message'];
										}
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($automailerror2);																
										} else {
											echo $lang['form-wait-message'];
											redirecttime($automailerror1);
										}
									} else {
										echo $lang['form-automessage-message'];
									}
								}	
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
			}
			
		} else {
			
			if ($mysql == true){
				
				if($signature == true){
									
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_payment($tablenamepayment,$insertpaymentvalues);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {														
										db_insert_payment($tablenamepayment,$insertpaymentvalues);	
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
																																								
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_payment($tablenamepayment,$insertpaymentvalues);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_payment($tablenamepayment,$insertpaymentvalues);	
											redirecttime($paymentsuccess1);
										}
									} else {														
										db_insert_payment($tablenamepayment,$insertpaymentvalues);	
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
				
			} else {
				
				if($signature == true){
									
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {														
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
					
				} else {
					
					$finalstripetoken = \Stripe\Token::create(array(
						'card' => array(
							'name' => $finalstripecardname,
							'number' => $finalstripecardnumber,
							'exp_month' => $finalstripecardmonth,
							'exp_year' => $finalstripecardyear,
							'cvc' => $finalstripecardcvc,
						)
					));
				
					$stripecustomer = \Stripe\Customer::create(array(
						'id' => $finalcustomerid,
						'email'  => $finalemail,
						'source'   => $finalstripetoken,
						'description' => $onetimestripedesc
					));
					
					if($stripecustomer){
						
						$stripecharge = \Stripe\Charge::create(array(
							'customer' => $finalcustomerid,
							'currency' => $paymentcurrency,
							'amount' => $finalstripeprice,
							'description' => $onetimestripedesc,
							'statement_descriptor' => $company
						));
						
						if($stripecharge){
																																								
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-payment-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($paymentsuccess1);
										}
									} else {														
										echo $lang['form-success-payment-message'];
									}
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($mailerror2);																
									} else {
										echo $lang['form-wait-message'];
										redirecttime($mailerror1);
									}
								} else {
									echo $lang['form-mail-message'];
								}
							}
						} else {
							if($redirect == true){
								if($multilanguage == true) {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror2);																
								} else {
									echo $lang['form-wait-message'];
									redirecttime($transactionerror1);
								}
							} else {
								echo $lang['form-transaction-message'];
							}										
						}
					} else {
						if($redirect == true){
							if($multilanguage == true) {
								echo $lang['form-wait-message'];
								redirecttime($customererror2);																
							} else {
								echo $lang['form-wait-message'];
								redirecttime($customererror1);
							}
						} else {
							echo $lang['form-customer-message'];
						}										
					}
				}
			}
		}
	}
	
?>