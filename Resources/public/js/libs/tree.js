/*
 * This file is part of the Tadcka package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function SitemapTree() {
    var $tree = $('div#tadcka-sitemap-tree');

    var $jsTree = $tree
        .jstree({
            "core": {
                'data': {
                    'url': function ($node) {
                        return $node.id === '#'
                            ? Routing.generate('tadcka_sitemap_tree_node_root')
                            : Routing.generate('tadcka_sitemap_tree_node', {id: $node.id });
                    }
                }
            }
        });

    /**
     * Get jsTree.
     *
     * @returns {jsTree}
     */
    this.getJsTree = function () {
        return $jsTree;
    };

    /**
     * Refresh tree.
     */
    this.refresh = function () {
        $tree.jstree().refresh();
    };
}