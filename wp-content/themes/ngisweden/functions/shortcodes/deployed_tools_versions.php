<?php

// NGI Deployed Tools Versions
// Renders the file generated on the cluster during deployment,
// showing which versions of which tools are currently in production.
function ngisweden_deployed_tools_versions_shortcode($atts_raw){

    // Fetch the deployment version file
    $deployed_file_contents = @file_get_contents(get_template_directory().'/cache/deployed_tools.version');

    $deployment_type = '';
    $deployed_at = '';
    $tools = [];

    foreach(preg_split("/\r\n|\n|\r/", $deployed_file_contents) as $line){
        if(substr($string_n, 0, 14) == '-- Deployed at'){
            $deployed_at = trim(str_replace('--', '', $line));
        }
        else if(substr($string_n, 0, 2) == '--'){
            $deployment_type = trim(str_replace('--', '', $line));
        }
        else {
            list($tool, $version) = explode(':', $line, 2);
            $tools[] = [trim($tool), trim($version)];
        }
    }

    if(count($tools) > 0){
        $output = '<div class="deployed-tools-versions">';
        $output .= '<h3>'.$deployment_type.'</h3>';
        $output .= '<p>'.$deployed_at.'</p>';
        $output .= '<table class="table table-striped">';
        $output .= '<thead><tr><th>Tool</th><th>Version</th></tr></thead>';
        $output .= '<tbody>';
        foreach($tools as $tool){
            $output .= '<tr><td>'.$tool[0].'</td><td>'.$tool[1].'</td></tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';
    }
    else {
        $output = '<div class="deployed-tools-versions">';
        $output .= '<h3>No tools deployed</h3>';
        $output .= '</div>';
    }

    return $output;

}
add_shortcode('ngisweden_deployed_tools_versions', 'ngisweden_deployed_tools_versions_shortcode');
