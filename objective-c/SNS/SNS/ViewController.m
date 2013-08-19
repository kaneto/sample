//
//  ViewController.m
//  SNS
//
//  Created by 木村 兼人 on 2013/08/20.
//  Copyright (c) 2013年 Kaneto. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)tweet {
    SLComposeViewController* controlloer = [SLComposeViewController composeViewControllerForServiceType:SLServiceTypeTwitter];
    [self presentViewController:controlloer animated:YES completion:NULL];
}
@end
