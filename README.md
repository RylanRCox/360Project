[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-24ddc0f5d75046c5622901739e7c5dd533143b0c8e959d652212380cedb1ea36.svg)](https://classroom.github.com/a/enf2qyfT)
# COSC360-Project
Chat forum website inspired by Reddit designed for UCBOs COSC 360 Web Programming Course.

## General Description
We will be doing project option 2, MyDiscussionForum Website. The website will function like a combination of reddit and 4chan and be called Breaddit. It will have different forum pages called Slices where discussion will be focused to a general topic. 

There will be three type of users on this account:
 - Regular Users: They will have an account with a distinct username, a personalized image, and a bio. They will be able to post discussions as well as respond with comments to other users posts or comments.
 - Adminstrators: Admins will be able to delete user accounts from the website. They will also be able to remove a discussion post or comment regardless of what Slice it is posted on.

## Requirements

### Basic Requirements
This project will contain all the minimum required functions which are as follows:
 - Hand-styled Layout with Contextual Menus
 - 2 column layout using appropriate design principles
 - Form Validation with JavaScript
 - Server-side Scripting with PHP
 - Data storage in MySQL
 - Appropriate Security for data
 - Site must maintain state
 - Responsive design philosophy
 - Ajax utilization for asynchronous updates
 - User Images
 - Simple discussion grouping and display
 - Navigation breadcrumb strategy
 - Error handling
 - Administrators

### Additional Requirements
Our project will contain the following additional functions

#### Header Bar
Displayed at the top of the webpage will have a header bar that will contain each of the following links and functions:
 - Website Logo
 - Search function
 - My Profile
 - Notifications
 - My Posts
 - My Comments
 - My Slices

#### Profile
Users will also be able to create profiles with distinct usernames and profile images. They will also be able to write a small description of themselves.

#### Slices
These will work similarly to Reddit's subreddits. Each discussion post will be posted to a specific Slice and Slices will be specific to certain topics. Certain users will have moderaton power over these Slices. Users will be able to follow these. Users will also be able to create these making themselves the moderator of Slices they create.

#### Collapsable Discussion Posts and Comments
Users can post discussions to Slices which then can be commented on by other users. Users will be able to delete their own posts and comments but not others. If that user is a moderator for the Slice in which the post is located that moderator can delete any post or comment. Comments on a post will be able to be collapsed and out of view.

#### Tracking a User's comment and post history
A user will be able to view all of their posts and comments and then use these to direct themselves back to discussion post.

#### Notifications
Users will get a notification on their header bar when another user has interacted with on of their posts or comments.

#### Search Function
Users will be able to use the sarch function to search for Slices or titles of discussion posts.

#### Hot Posts
On the homepage users will be shown the hot posts from each Slice they are following.
