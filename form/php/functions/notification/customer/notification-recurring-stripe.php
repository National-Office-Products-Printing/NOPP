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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
							
									include 'messages/customer/message-copy-recurring.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess2);																
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
													redirecttime($subscriptionsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												echo $lang['form-success-recurring-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess1);
												}
											} else {														
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
							
									include 'messages/customer/message-copy-recurring.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess2);																
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
													redirecttime($subscriptionsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												echo $lang['form-success-recurring-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess2);
												} else {
													echo $lang['form-wait-message'];
													db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
													redirecttime($subscriptionsuccess1);
												}
											} else {														
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
							
									include 'messages/customer/message-copy-recurring.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess2);																
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-recurring-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess1);
												}
											} else {														
												echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
							
									include 'messages/customer/message-copy-recurring.php';
									include 'emailcopy.php';
									
									if($emailcopy->send()){
										if($sendmobile == true){
											if($redirect == true){
												if($multilanguage == true) {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess2);																
												} else {
													$mobile->send($notification, $phone, $unicode);
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess1);
												}
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-success-recurring-message'];
											}
										} else {
											if($redirect == true){
												if($multilanguage == true) {
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess2);
												} else {
													echo $lang['form-wait-message'];
													redirecttime($subscriptionsuccess1);
												}
											} else {														
												echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
									
								include 'messages/customer/message-copy-recurring.php';
								include 'emailcopy.php';
								
								if($emailcopy->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/message-copy-recurring.php';
								include 'emailcopy.php';
								
								if($emailcopy->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/message-copy-recurring.php';
								include 'emailcopy.php';
								
								if($emailcopy->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/message-copy-recurring.php';
								include 'emailcopy.php';
								
								if($emailcopy->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
							
								include 'messages/customer/automessage-recurring.php';
								include 'automessage.php';
							
								if($automail->send()){
									if($sendmobile == true){
										if($redirect == true){
											if($multilanguage == true) {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);																
											} else {
												$mobile->send($notification, $phone, $unicode);
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-success-recurring-message'];
										}
									} else {
										if($redirect == true){
											if($multilanguage == true) {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess2);
											} else {
												echo $lang['form-wait-message'];
												redirecttime($subscriptionsuccess1);
											}
										} else {														
											echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
							
							include 'functions/signature/signature.php';
							
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										echo $lang['form-success-recurring-message'];
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
							'plan'   => $finalrecurringplan,
							'source'   => $finalstripetoken,
							'description' => $recurringstripedesc
						));
						
						if($stripecustomer){
													
							if($adminemail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
						
						include 'functions/signature/signature.php';
						
						if($adminemail->send()){
						
							include 'messages/customer/automessage-recurring.php';
							include 'automessage.php';
						
							if($automail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
												
						if($adminemail->send()){
						
							include 'messages/customer/automessage-recurring.php';
							include 'automessage.php';
						
							if($automail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
						
						include 'functions/signature/signature.php';
						
						if($adminemail->send()){
						
							include 'messages/customer/automessage-recurring.php';
							include 'automessage.php';
						
							if($automail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
												
						if($adminemail->send()){
						
							include 'messages/customer/automessage-recurring.php';
							include 'automessage.php';
						
							if($automail->send()){
								if($sendmobile == true){
									if($redirect == true){
										if($multilanguage == true) {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);																
										} else {
											$mobile->send($notification, $phone, $unicode);
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-success-recurring-message'];
									}
								} else {
									if($redirect == true){
										if($multilanguage == true) {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess2);
										} else {
											echo $lang['form-wait-message'];
											redirecttime($subscriptionsuccess1);
										}
									} else {														
										echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
						
						include 'functions/signature/signature.php';
						
						if($adminemail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess2);																
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										redirecttime($subscriptionsuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
									echo $lang['form-success-recurring-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess2);
									} else {
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess1);
									}
								} else {														
									db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
									echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
												
						if($adminemail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess2);																
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
										redirecttime($subscriptionsuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									db_insert_recurring($tablenamerecurring,$insertrecurringvalues);
									echo $lang['form-success-recurring-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess2);
									} else {
										echo $lang['form-wait-message'];
										db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
										redirecttime($subscriptionsuccess1);
									}
								} else {														
									db_insert_recurring($tablenamerecurring,$insertrecurringvalues);	
									echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
						
						include 'functions/signature/signature.php';
						
						if($adminemail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess2);																
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-success-recurring-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess2);
									} else {
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess1);
									}
								} else {														
									echo $lang['form-success-recurring-message'];
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
						'plan'   => $finalrecurringplan,
						'source'   => $finalstripetoken,
						'description' => $recurringstripedesc
					));
					
					if($stripecustomer){
												
						if($adminemail->send()){
							if($sendmobile == true){
								if($redirect == true){
									if($multilanguage == true) {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess2);																
									} else {
										$mobile->send($notification, $phone, $unicode);
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess1);
									}
								} else {
									$mobile->send($notification, $phone, $unicode);
									echo $lang['form-success-recurring-message'];
								}
							} else {
								if($redirect == true){
									if($multilanguage == true) {
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess2);
									} else {
										echo $lang['form-wait-message'];
										redirecttime($subscriptionsuccess1);
									}
								} else {														
									echo $lang['form-success-recurring-message'];
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