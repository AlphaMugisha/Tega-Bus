# Tega-Bus — Transit Application

## Overview

A transit-focused software repository organized around a `transiteco-api/` backend directory, a `transiteco-web/` frontend entry, and a PowerShell script for simulating tracking activity. The repository name and structure indicate a public-transport or bus-tracking project; this README avoids claiming specific live integrations that have not been verified.

## Mission

Explore how digital tools can make public transport information and bus operations easier to access and manage.

## Goals

- Develop a clear separation between the transit API and web interface.
- Establish reliable data structures for routes, vehicles, and tracking information as the implementation defines them.
- Make transit information understandable to passengers and operators.
- Use simulation tools to test tracking workflows during development.
- Keep API configuration and service credentials private.

## Repository structure

- `transiteco-api/` — backend/API project
- `transiteco-web/` — web application entry
- `simulate_tracking.ps1` — tracking simulation helper

## Getting started

Start by reading the README and package configuration inside `transiteco-api/` and the instructions associated with `transiteco-web/`. Install dependencies separately for each application where required. Use example environment files for local configuration and never commit live secrets.

## Development priorities

Document the actual API endpoints and supported tracking data, test behavior with simulated data, and clearly distinguish simulated tracking from live vehicle data.
